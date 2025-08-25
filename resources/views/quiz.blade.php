<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Living Books — Demo Phòng Chống Đuối Nước</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <header>
      <div class="wrap brand">
        <svg
          width="28"
          height="28"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#2563eb"
          stroke-width="2"
        >
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
          <path d="M20 22V8a2 2 0 0 0-2-2h-7l-2-2H6a2 2 0 0 0-2 2v12" />
        </svg>
        <b>Living Books</b>
        <span class="muted" id="kidTag"></span>
      </div>
    </header>

    <main class="wrap">
      <div id="view"></div>
    </main>

    <canvas class="confetti" id="confetti"></canvas>

    <script>
      window.Laravel = { csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content') };
    </script>
    <script src="{{ asset('js/quiz.js') }}"></script>
  </body>
 </html>


