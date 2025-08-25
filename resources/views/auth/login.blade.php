<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
  </head>
  <body>
    <main class="wrap">
      <div class="card">
        <h2>🔐 Đăng nhập</h2>
        @if ($errors->any())
          <div class="notice" style="color:#dc2626;">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login.submit') }}" style="display:grid; gap:12px; margin-top:12px;">
          @csrf
          <input type="hidden" name="book" value="{{ $book }}" />
          <input type="hidden" name="lesson" value="{{ $lesson }}" />
          <input name="username" placeholder="Tên đăng nhập" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" value="{{ old('username') }}" />
          <input type="password" name="password" placeholder="Mật khẩu" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
          <button class="btn btn-primary" type="submit">Đăng nhập</button>
        </form>
        <div class="foot">
          <a class="btn btn-ghost" href="{{ route('register', ['book' => $book, 'lesson' => $lesson]) }}">Chưa có tài khoản? Đăng ký</a>
        </div>
      </div>
    </main>
  </body>
 </html>


