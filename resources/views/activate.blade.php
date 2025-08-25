<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kích hoạt mã</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
  </head>
  <body>
    <main class="wrap">
      <div class="card">
        <h2>🔑 Kích hoạt sách</h2>
        @if ($errors->any())
          <div class="notice" style="color:#dc2626;">{{ $errors->first() }}</div>
        @endif
        <form id="activateForm" method="POST" action="{{ route('activate.submit') }}" style="display:grid; gap:12px; margin-top:12px;">
          @csrf
          <input name="code" placeholder="Mã kích hoạt in trong sách" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
          <input name="name" placeholder="Tên học sinh" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
          <input name="age" placeholder="Tuổi (ví dụ: 8-10)" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
          @if(request('book'))
            <input type="hidden" name="book" value="{{ request('book') }}" />
          @endif
          <input type="hidden" name="kid" id="kidHidden" value="{{ request('kid') }}" />
          @if(request('lesson'))
            <input type="hidden" name="lesson" value="{{ request('lesson') }}" />
          @endif
          <button class="btn btn-primary" type="submit">Kích hoạt</button>
        </form>
      </div>
    </main>
    <script>
      // Tự sinh kid UID nếu chưa có và lưu localStorage để dùng cho lần sau
      (function(){
        try {
          var kid = localStorage.getItem('kid_uid');
          if (kid) { kid = JSON.parse(kid); }
          if (!kid || !String(kid).trim()) {
            // UUID v4 đơn giản
            kid = 'K-' + ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
              (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
            localStorage.setItem('kid_uid', JSON.stringify(kid));
          }
          var kidInput = document.getElementById('kidHidden');
          if (kidInput && !kidInput.value) kidInput.value = kid;
        } catch(e) {}
      })();
      // Lưu name/age localStorage khi submit để lần sau quiz bỏ qua prompt
      (function(){
        try {
          var form = document.getElementById('activateForm');
          form.addEventListener('submit', function(){
            var name = form.querySelector('input[name="name"]').value;
            var age = form.querySelector('input[name="age"]').value;
            if (name) localStorage.setItem('student_name', JSON.stringify(name));
            if (age) localStorage.setItem('student_age', JSON.stringify(age));
          });
        } catch(e) {}
      })();
    </script>
  </body>
 </html>


