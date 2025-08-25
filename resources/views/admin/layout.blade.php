<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
  </head>
  <body>
    <main class="wrap">
      <div class="foot" style="justify-content:space-between;">
        <div>
          <a class="btn btn-ghost" href="{{ route('admin.books.index') }}">📚 Sách</a>
          <a class="btn btn-ghost" href="{{ route('admin.users.index') }}">👤 Users</a>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn btn-ghost" type="submit">Đăng xuất</button></form>
      </div>
      <div class="card">
        @yield('content')
      </div>
    </main>
  </body>
 </html>


