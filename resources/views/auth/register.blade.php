<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đăng ký</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
  </head>
  <body>
    <main class="wrap">
      <div class="card">
        <h2>📝 Đăng ký</h2>
        @if ($errors->any())
          <div class="notice" style="color:#dc2626;">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('register.submit') }}" style="display:grid; gap:12px; margin-top:12px;">
          @csrf
          <input type="hidden" name="book" value="{{ $book }}" />
          <input type="hidden" name="lesson" value="{{ $lesson }}" />
          <input name="username" placeholder="Tên đăng nhập" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" value="{{ old('username') }}" />
          <input type="password" name="password" placeholder="Mật khẩu" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
          <input name="age" placeholder="Tuổi (ví dụ: 8-10)" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" value="{{ old('age') }}" />
          <button class="btn btn-primary" type="submit">Tạo tài khoản</button>
        </form>
        <div class="foot">
          <a class="btn btn-ghost" href="{{ route('login', ['book' => $book, 'lesson' => $lesson]) }}">Đã có tài khoản? Đăng nhập</a>
        </div>
      </div>
    </main>
  </body>
 </html>


