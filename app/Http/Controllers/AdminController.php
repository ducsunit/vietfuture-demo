<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use AdminContentBuilder;
    // Dashboard list
    public function booksIndex()
    {
        $books = Book::orderByDesc('id')->paginate(20);
        return view('admin.books.index', compact('books'));
    }

    public function booksCreate()
    {
        return view('admin.books.create');
    }

    public function booksStore(Request $request)
    {
        $data = $request->validate([
            'book_uid' => 'required|string|unique:books,book_uid',
            'title' => 'required|string',
            'content' => 'nullable',
            // structured lesson inputs
            'lesson_id' => 'nullable|string',
            'lesson_title' => 'nullable|string',
            'q' => 'array',
        ]);
        $payload = [
            'book_uid' => $data['book_uid'],
            'title' => $data['title'],
        ];
        $content = $this->buildContentFromRequest($request);
        if ($content) {
            $payload['content'] = json_encode($content, JSON_UNESCAPED_UNICODE);
        }
        Book::create($payload);
        return redirect()->route('admin.books.index')->with('success', 'Đã tạo sách thành công!');
    }

    public function booksEdit(int $id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function booksUpdate(Request $request, int $id)
    {
        $book = Book::findOrFail($id);
        $data = $request->validate([
            'book_uid' => 'required|string|unique:books,book_uid,' . $book->id,
            'title' => 'required|string',
            'content' => 'nullable',
            'lesson_id' => 'nullable|string',
            'lesson_title' => 'nullable|string',
            'q' => 'array',
        ]);
        $payload = [
            'book_uid' => $data['book_uid'],
            'title' => $data['title'],
        ];
        $content = $this->buildContentFromRequest($request);
        if ($content) {
            $payload['content'] = json_encode($content, JSON_UNESCAPED_UNICODE);
        }
        $book->update($payload);
        return redirect()->route('admin.books.index')->with('success', 'Đã cập nhật sách thành công!');
    }

    public function booksDestroy(int $id)
    {
        $book = Book::findOrFail($id);
        $bookTitle = $book->title;
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', "Đã xóa sách '{$bookTitle}' thành công!");
    }

    // Users
    public function usersIndex()
    {
        $users = User::orderByDesc('id')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users.create');
    }

    public function usersStore(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'password' => 'required|string|min:6|max:100',
            'display_name' => 'nullable|string|max:100',
            'age' => 'nullable|string|max:20',
            'point' => 'nullable|integer|min:0',
            'role' => 'nullable|integer|in:0,1',
        ]);
        
        // Set default values
        $data['point'] = $data['point'] ?? 0;
        $data['role'] = $data['role'] ?? 0;
        
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'Đã tạo user thành công!');
    }

    public function usersEdit(int $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|max:100',
            'display_name' => 'nullable|string|max:100',
            'age' => 'nullable|string|max:20',
            'point' => 'nullable|integer|min:0',
            'role' => 'nullable|integer|in:0,1',
        ]);
        
        // Remove empty password
        if (empty($data['password'])) unset($data['password']);
        
        // Set default values
        $data['point'] = $data['point'] ?? $user->point ?? 0;
        $data['role'] = $data['role'] ?? $user->role ?? 0;
        
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật user thành công!');
    }

    public function usersDestroy(int $id)
    {
        $user = User::findOrFail($id);
        $username = $user->username;
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', "Đã xóa user '{$username}' thành công!");
    }
}

// Helpers
namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;

trait AdminContentBuilder
{
    protected function buildContentFromRequest(HttpRequest $request): ?array
    {
        $lessonId = $request->input('lesson_id');
        $lessonTitle = $request->input('lesson_title');
        $questions = $request->input('q', []);
        if (!$lessonId || !$lessonTitle) {
            return null;
        }
        $outQuestions = [];
        foreach ($questions as $q) {
            if (!isset($q['type']) || !isset($q['text'])) continue;
            $entry = [ 'id' => $q['id'] ?? (uniqid('q')), 'type' => $q['type'], 'text' => $q['text'] ];
            if ($q['type'] === 'single') {
                $opts = [];
                foreach (['a','b','c','d'] as $optId) {
                    if (!empty($q['opt_'.$optId])) {
                        $opt = [ 'id' => $optId, 'text' => $q['opt_'.$optId] ];
                        if (!empty($q['correct']) && $q['correct'] === $optId) $opt['correct'] = true;
                        $opts[] = $opt;
                    }
                }
                $entry['options'] = $opts;
                if (!empty($q['explain'])) $entry['explain'] = $q['explain'];
            }
            if ($q['type'] === 'order') {
                $items = array_filter(array_map('trim', explode(',', $q['items'] ?? '')));
                $answer = array_filter(array_map('trim', explode(',', $q['answer'] ?? '')));
                $entry['items'] = $items;
                $entry['answer'] = $answer;
            }
            $outQuestions[] = $entry;
        }
        return [ 'lessons' => [ [ 'id' => $lessonId, 'title' => $lessonTitle, 'questions' => $outQuestions ] ] ];
    }
}


