<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>📊 Bảng Theo Dõi Học Tập - VietFuture</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Comic+Neue:wght@300;400;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
          <h1 class="logo-text">📊 VietFuture Dashboard</h1>
        </div>
        
        @if(session('user_id'))
          <nav class="nav-menu">
            <a href="{{ route('welcome') }}" class="nav-link">
              <span class="nav-emoji">🏠</span>
              <span>Trang chủ</span>
            </a>
            <a href="{{ route('quiz') }}" class="nav-link">
              <span class="nav-emoji">🎮</span>
              <span>Quiz</span>
            </a>
            <a href="{{ route('parent') }}" class="nav-link active">
              <span class="nav-emoji">👨‍👩‍👧</span>
              <span>Dashboard</span>
            </a>
            <a href="{{ route('community.index') }}" class="nav-link">
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
        <h2>📊 Theo dõi tiến trình học</h2>
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

    </main>
    @if(session('user_id'))
    <script>
      // Không cần localStorage nữa vì đã có user đăng nhập
      // Display name sẽ được lưu trong database và quản lý qua API
    </script>
    @endif
  </body>
 </html>


