<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Book;
use App\Models\User;
use App\Models\ProgressRecord;
use App\Models\CommunityThread;
use App\Models\CommunityComment;
use Illuminate\Http\JsonResponse;

class DemoController extends Controller
{
    public function quiz(Request $request)
    {
        $kid = $request->query('kid');
        $bookUid = $request->query('book');
        if ($bookUid) {
            $hasUser = session('user_id') !== null;
            if (!$hasUser) {
                return redirect()->route('login', [
                    'book' => $bookUid,
                    'lesson' => $request->query('lesson')
                ]);
            }
        }
        return view('quiz');
    }

    public function logProgress(Request $request)
    {
        $data = $request->validate([
            'kidId' => 'nullable|string',
            'lesson' => 'required|string',
            'score' => 'required|integer',
            'age' => 'nullable|string',
            'name' => 'required|string|max:100',
            'userId' => 'nullable|integer',
        ]);

        // Tìm user từ session hoặc userId
        $targetUser = null;
        if ($request->session()->has('user_id')) {
            $targetUser = User::find($request->session()->get('user_id'));
        }
        if (!$targetUser && $request->filled('userId')) {
            $targetUser = User::find($request->input('userId'));
        }

        // Lưu progress record vào database
        $progressRecord = ProgressRecord::create([
            'record_uid' => (string) Str::uuid(),
            'user_id' => $targetUser ? $targetUser->id : null,
            'kid_id' => $data['kidId'],
            'lesson' => $data['lesson'],
            'score' => $data['score'],
            'age' => $data['age'] ?? null,
            'name' => $data['name'],
        ]);

        // Cộng điểm cho user
        if ($targetUser) {
            $targetUser->point = (int) $targetUser->point + (int) $data['score'];
            $targetUser->save();
            if ($request->session()) {
                $request->session()->put('point', (int) $targetUser->point);
            }
        }

        return response()->json(['ok' => true, 'record_id' => $progressRecord->id]);
    }



    public function parentDashboard()
    {
        // Lấy progress records từ database, sắp xếp theo thời gian mới nhất
        $records = ProgressRecord::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50) // Giới hạn 50 records gần nhất
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->record_uid,
                    'kidId' => $record->kid_id,
                    'name' => $record->name,
                    'lesson' => $record->lesson,
                    'score' => $record->score,
                    'age' => $record->age,
                    'created_at' => $record->created_at->format('Y-m-d H:i:s'),
                    'user' => $record->user ? $record->user->username : null,
                ];
            });

        return view('parent', ['records' => $records]);
    }

    public function communityIndex()
    {
        // Lấy threads từ database với comments
        $threads = CommunityThread::with(['comments', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($thread) {
                return [
                    'id' => $thread->thread_uid,
                    'title' => $thread->title,
                    'content' => $thread->content,
                    'author' => $thread->author,
                    'created_at' => $thread->created_at->format('Y-m-d H:i:s'),
                    'user' => $thread->user ? $thread->user->username : null,
                    'comments' => $thread->comments->map(function ($comment) {
                        return [
                            'id' => $comment->comment_uid,
                            'content' => $comment->content,
                            'author' => $comment->author,
                            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                            'user' => $comment->user ? $comment->user->username : null,
                        ];
                    })->toArray(),
                ];
            });

        return view('community', ['threads' => $threads]);
    }

    public function communityCreate(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string|max:5000',
            'author' => 'required|string|max:100',
        ]);

        $userId = $request->session()->get('user_id');

        // Tạo thread mới trong database
        CommunityThread::create([
            'thread_uid' => (string) Str::uuid(),
            'user_id' => $userId,
            'title' => $data['title'],
            'content' => $data['content'],
            'author' => $data['author'],
        ]);

        return redirect()->route('community');
    }

    public function communityComment(Request $request, string $id)
    {
        $data = $request->validate([
            'comment' => 'required|string|max:1000',
            'author' => 'required|string|max:100',
        ]);

        $userId = $request->session()->get('user_id');

        // Tìm thread theo thread_uid
        $thread = CommunityThread::where('thread_uid', $id)->first();

        if (!$thread) {
            return redirect()->route('community')->with('error', 'Thread không tồn tại');
        }

        // Tạo comment mới
        CommunityComment::create([
            'comment_uid' => (string) Str::uuid(),
            'thread_id' => $thread->id,
            'user_id' => $userId,
            'content' => $data['comment'],
            'author' => $data['author'],
        ]);

        return redirect()->route('community');
    }

    public function getLesson(Request $request): JsonResponse
    {
        $bookUid = $request->query('book');
        $lessonId = $request->query('lesson');

        // Kiểm tra tham số đầu vào
        if (!$bookUid || !$lessonId) {
            return response()->json([
                'book' => null,
                'lesson' => null,
                'error' => 'Missing book or lesson parameter'
            ], 400);
        }

        // Tìm book với error handling
        $book = Book::where('book_uid', $bookUid)->first();
        if (!$book) {
            return response()->json([
                'book' => null,
                'lesson' => null,
                'error' => 'Book not found'
            ], 404);
        }

        $content = $book->content ? json_decode($book->content, true) : [];
        $lesson = null;

        if (isset($content['lessons']) && is_array($content['lessons'])) {
            foreach ($content['lessons'] as $ls) {
                if ((string)($ls['id'] ?? '') === (string)$lessonId) {
                    $lesson = $ls;
                    break;
                }
            }
        }

        return response()->json([
            'book' => ['uid' => $book->book_uid, 'title' => $book->title],
            'lesson' => $lesson,
        ]);
    }
}
