<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>🌍 Cộng Đồng Kỹ Năng Sống - VietFuture</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Comic+Neue:wght@300;400;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}" />
  </head>
  <body>
    <header class="header-nav fade-in">
      <div class="header-container">
        <div class="logo-section">
          <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
              <path d="M20 22V8a2 2 0 0 0-2-2h-7l-2-2H6a2 2 0 0 0-2 2v12" />
            </svg>
          </div>
          <h1 class="logo-text">🌍 VietFuture Community</h1>
        </div>
        
        @if(session('user_id'))
          <nav class="nav-menu">
            <a href="{{ route('login', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="nav-link">
              <span class="nav-emoji">🏠</span>
              <span>Trang chủ</span>
            </a>
            <a href="{{ route('quiz') }}" class="nav-link">
              <span class="nav-emoji">🎮</span>
              <span>Quiz</span>
            </a>
            <a href="{{ route('parent') }}" class="nav-link">
              <span class="nav-emoji">👨‍👩‍👧</span>
              <span>Dashboard</span>
            </a>
            <a href="{{ route('community') }}" class="nav-link active">
              <span class="nav-emoji">💬</span>
              <span>Cộng đồng</span>
            </a>
            <a href="#" onclick="alert('Cửa hàng chỉ có trong trang Quiz')" class="nav-link disabled">
              <span class="nav-emoji">🛍️</span>
              <span>Cửa hàng</span>
            </a>
            <a href="#" onclick="alert('Bộ sưu tập chỉ có trong trang Quiz')" class="nav-link disabled">
              <span class="nav-emoji">📚</span>
              <span>Bộ sưu tập</span>
            </a>
          </nav>
          
          <div class="user-section">
            <span class="user-name">
              <span>👤</span>
              {{ session('username', 'User') }}
            </span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
              @csrf
              <button type="submit" class="logout-btn">
                <span>🚪</span>
                <span>Đăng xuất</span>
              </button>
            </form>
          </div>
        @endif
      </div>
    </header>
    <main class="wrap">
      <div class="card">
        <h2>✍️ Chia sẻ cách dạy kỹ năng sống</h2>
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


    </main>
    @if(session('user_id'))
    <script>
      // Sử dụng display_name từ database thay vì localStorage
      (async function() {
        try {
          let displayName = '{{ session("username", "User") }}';
          
          // Lấy display_name từ database nếu có
          const response = await fetch('/api/get-display-name', {
            headers: { 'Accept': 'application/json' }
          });
          
          if (response.ok) {
            const data = await response.json();
            if (data.ok && data.display_name) {
              displayName = data.display_name;
            }
          }
          
          // Set author fields
          const mainAuthor = document.getElementById('authorField');
          if (mainAuthor) mainAuthor.value = displayName;
          
          const commentAuthors = document.querySelectorAll('.authorFieldComment');
          commentAuthors.forEach(function(el) { 
            el.value = displayName; 
          });
          
        } catch(e) {
          console.log('Could not get display name:', e);
          // Fallback sử dụng username
          const displayName = '{{ session("username", "User") }}';
          const mainAuthor = document.getElementById('authorField');
          if (mainAuthor) mainAuthor.value = displayName;
          const commentAuthors = document.querySelectorAll('.authorFieldComment');
          commentAuthors.forEach(function(el) { el.value = displayName; });
        }
      })();
    </script>
    @else
    <script>
      // Guest user - sử dụng tên mặc định
      const defaultName = 'Khách';
      const mainAuthor = document.getElementById('authorField');
      if (mainAuthor) mainAuthor.value = defaultName;
      const commentAuthors = document.querySelectorAll('.authorFieldComment');
      commentAuthors.forEach(function(el) { el.value = defaultName; });
    </script>
    @endif
  </body>
 </html>


