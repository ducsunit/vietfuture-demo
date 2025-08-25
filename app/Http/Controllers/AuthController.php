<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        return view('auth.login', [
            'book' => $request->query('book'),
            'lesson' => $request->query('lesson'),
        ]);
    }

    public function showRegister(Request $request)
    {
        return view('auth.register', [
            'book' => $request->query('book'),
            'lesson' => $request->query('lesson'),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'password' => 'required|string|min:6|max:100',
            'age' => 'required|string|max:20',
        ]);
        if (!isset($data['role'])) $data['role'] = 0;
        $user = User::create($data);
        session(['user_id' => $user->id, 'username' => $user->username, 'point' => (int) $user->point]);
        if ((int) $user->role === 1) {
            return redirect()->route('admin.books.index');
        }
        $redirect = route('demo.quiz', [ 'book' => $request->input('book'), 'lesson' => $request->input('lesson') ]);
        return redirect($redirect);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('username', $data['username'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['username' => 'Sai tài khoản hoặc mật khẩu.'])->withInput();
        }
        session(['user_id' => $user->id, 'username' => $user->username, 'point' => (int) $user->point]);
        if ((int) $user->role === 1) {
            return redirect()->route('admin.books.index');
        }
        $redirect = route('demo.quiz', [ 'book' => $request->input('book'), 'lesson' => $request->input('lesson') ]);
        return redirect($redirect);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'username', 'point']);
        $request->session()->flush(); // Xóa toàn bộ session
        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    }
}


