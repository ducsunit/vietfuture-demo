<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\Book;
use App\Models\User;
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
        ]);

        $key = 'progress_records';
        $records = Cache::get($key, []);
        $records[] = [
            'id' => (string) Str::uuid(),
            'kidId' => $data['kidId'],
            'lesson' => $data['lesson'],
            'score' => $data['score'],
            'age' => $data['age'] ?? null,
            'name' => $data['name'],
            'created_at' => now()->toDateTimeString(),
        ];
        Cache::put($key, $records, now()->addDay());

        // Cộng điểm cho user theo session hoặc theo userId truyền vào
        $targetUser = null;
        if ($request->session()->has('user_id')) {
            $targetUser = User::find($request->session()->get('user_id'));
        }
        if (!$targetUser && $request->filled('userId')) {
            $targetUser = User::find($request->input('userId'));
        }
        if ($targetUser) {
            $targetUser->point = (int) $targetUser->point + (int) $data['score'];
            $targetUser->save();
            if ($request->session()) {
                $request->session()->put('point', (int) $targetUser->point);
            }
        }
        return response()->json(['ok' => true]);
    }

    public function registerName(Request $request)
    {
        $data = $request->validate([
            'kidId' => 'required|string',
            'name' => 'required|string|max:100',
            'age' => 'nullable|string',
        ]);
        $student = Student::firstOrCreate(['kid_uid' => $data['kidId']]);
        $student->name = $data['name'];
        if (isset($data['age'])) $student->age = $data['age'];
        $student->save();
        return response()->json(['ok' => true]);
    }

    public function parentDashboard()
    {
        $records = Cache::get('progress_records', []);
        // Sort desc by created_at
        usort($records, function ($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });
        return view('parent', ['records' => $records]);
    }

    public function communityIndex()
    {
        $threads = Cache::get('community_threads', []);
        return view('community', ['threads' => $threads]);
    }

    public function communityCreate(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string|max:5000',
            'author' => 'required|string|max:100',
        ]);
        $threads = Cache::get('community_threads', []);
        $thread = [
            'id' => (string) Str::uuid(),
            'title' => $data['title'],
            'content' => $data['content'],
            'author' => $data['author'],
            'created_at' => now()->toDateTimeString(),
            'comments' => [],
        ];
        array_unshift($threads, $thread);
        Cache::put('community_threads', $threads, now()->addDay());
        return redirect()->route('community.index');
    }

    public function communityComment(Request $request, string $id)
    {
        $data = $request->validate([
            'comment' => 'required|string|max:1000',
            'author' => 'required|string|max:100',
        ]);
        $threads = Cache::get('community_threads', []);
        foreach ($threads as &$t) {
            if ($t['id'] === $id) {
                $t['comments'][] = [
                    'id' => (string) Str::uuid(),
                    'content' => $data['comment'],
                    'author' => $data['author'],
                    'created_at' => now()->toDateTimeString(),
                ];
                break;
            }
        }
        Cache::put('community_threads', $threads, now()->addDay());
        return redirect()->route('community.index');
    }

    public function getLesson(Request $request): JsonResponse
    {
        $bookUid = $request->query('book');
        $lessonId = $request->query('lesson');
        $book = Book::where('book_uid', $bookUid)->firstOrFail();
        $content = $book->content ? json_decode($book->content, true) : [];
        $lesson = null;
        if (isset($content['lessons']) && is_array($content['lessons'])) {
            foreach ($content['lessons'] as $ls) {
                if ((string)($ls['id'] ?? '') === (string)$lessonId) { $lesson = $ls; break; }
            }
        }
        return response()->json([
            'book' => [ 'uid' => $book->book_uid, 'title' => $book->title ],
            'lesson' => $lesson,
        ]);
    }
}


