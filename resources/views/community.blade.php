<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cộng đồng kỹ năng sống</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
  </head>
  <body>
    <main class="wrap">
      <div class="card">
        <h2>💬 Chia sẻ cách dạy kỹ năng sống</h2>
        <form method="POST" action="{{ route('community.create') }}">
          @csrf
          <div style="display:grid; gap:8px;">
            <input type="hidden" name="author" id="authorField" />
            <input name="title" placeholder="Tiêu đề" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
            <textarea name="content" placeholder="Nội dung chia sẻ" required style="padding:10px; min-height:100px; border:1px solid #e2e8f0; border-radius:8px;"></textarea>
            <button class="btn btn-primary" type="submit">Đăng bài</button>
          </div>
        </form>
      </div>

      <div class="grid">
        @forelse($threads as $t)
          <div class="card">
            <h3 style="margin-top:0;">{{ $t['title'] }}</h3>
            <div class="muted" style="font-size:14px;">Đăng bởi: {{ $t['author'] ?? 'Ẩn danh' }}</div>
            <p>{{ $t['content'] }}</p>
            <div class="muted">{{ $t['created_at'] }}</div>
            <div style="margin-top:12px;">
              <b>Bình luận</b>
              <div style="display:grid; gap:8px; margin-top:8px;">
                @foreach(($t['comments'] ?? []) as $c)
                  <div style="border:1px solid #e2e8f0; border-radius:8px; padding:8px;">
                    <div><b>{{ $c['author'] ?? 'Ẩn danh' }}:</b> {{ $c['content'] }}</div>
                    <div class="muted" style="font-size:12px;">{{ $c['created_at'] }}</div>
                  </div>
                @endforeach
              </div>
            </div>
            <form method="POST" action="{{ route('community.comment', ['id' => $t['id']]) }}" style="margin-top:12px;">
              @csrf
              <div style="display:grid; gap:8px;">
                <input type="hidden" name="author" class="authorFieldComment" />
                <input name="comment" placeholder="Viết bình luận..." required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
                <button class="btn btn-ghost" type="submit">Gửi bình luận</button>
              </div>
            </form>
          </div>
        @empty
          <div class="notice">Chưa có bài viết nào. Hãy là người đầu tiên!</div>
        @endforelse
      </div>

      <div class="foot">
        <a class="btn btn-ghost" href="{{ route('demo.quiz') }}">▶ Chơi demo</a>
        <a class="btn btn-primary" href="{{ route('parent.dashboard') }}">👨‍👩‍👧 Phụ huynh</a>
      </div>
    </main>
    <script>
      (function() {
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
            name = 'Học sinh';
          }
          var mainAuthor = document.getElementById('authorField');
          if (mainAuthor) mainAuthor.value = name;
          var commentAuthors = document.querySelectorAll('.authorFieldComment');
          commentAuthors.forEach(function(el){ el.value = name; });
        } catch(e) {}
      })();
    </script>
  </body>
 </html>


