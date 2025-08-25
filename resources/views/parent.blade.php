<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bảng điều khiển phụ huynh</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <main class="wrap">
      <div class="card">
        <h2>👨‍👩‍👧 Theo dõi tiến trình học</h2>
        <p class="muted">Danh sách bản ghi gần đây.</p>
        <div class="grid">
          @forelse($records as $r)
            <div class="card">
              <div><b>Học sinh:</b> {{ $r['name'] ?? '—' }} ({{ $r['kidId'] }})</div>
              <div><b>Bài học:</b> {{ $r['lesson'] }}</div>
              <div><b>Điểm:</b> {{ $r['score'] }}</div>
              <div><b>Tuổi:</b> {{ $r['age'] ?? '—' }}</div>
              <div class="muted">{{ $r['created_at'] }}</div>
            </div>
          @empty
            <div class="notice">Chưa có bản ghi nào.</div>
          @endforelse
        </div>
      </div>
      <div class="foot">
        <a class="btn btn-ghost" href="{{ route('demo.quiz') }}">▶ Chơi demo</a>
        <a class="btn btn-primary" href="{{ route('community.index') }}">💬 Cộng đồng</a>
      </div>
    </main>
    <script>
      (async function ensureName() {
        try {
          var name = localStorage.getItem('student_name');
          if (name) { name = JSON.parse(name); }
          if (!name || !String(name).trim()) {
            var params = new URLSearchParams(window.location.search);
            var kid = params.get('kid') || 'KID-DEMO';
            var byKid = localStorage.getItem('name_' + kid);
            if (byKid) { name = JSON.parse(byKid); }
          }
          if (!name || !String(name).trim()) {
            if (window.Swal && typeof window.Swal.fire === 'function') {
              let valid = false;
              while (!valid) {
                const result = await Swal.fire({
                  title: 'Xin chào! Hãy nhập tên của bạn',
                  input: 'text',
                  inputLabel: 'Tên sẽ dùng xuyên suốt quá trình học',
                  inputPlaceholder: 'VD: Minh Anh',
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: 'Xác nhận',
                  inputValidator: (value) => {
                    if (!value || value.trim().length < 2) return 'Tên phải có ít nhất 2 ký tự';
                    return undefined;
                  }
                });
                if (result && result.value && String(result.value).trim().length >= 2) {
                  name = String(result.value).trim();
                  localStorage.setItem('student_name', JSON.stringify(name));
                  valid = true;
                }
              }
            } else {
              // Fallback
              var n = '';
              while (!n || n.trim().length < 2) {
                n = prompt('Nhập tên của bạn để bắt đầu:');
                if (n && n.trim().length >= 2) {
                  name = n.trim();
                  localStorage.setItem('student_name', JSON.stringify(name));
                }
              }
            }
          }
        } catch(e) {}
      })();
    </script>
  </body>
 </html>


