<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard') - VietFuture</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body class="admin-body">
    <header class="admin-header">
      <div class="admin-header-container">
        <div class="admin-logo">
          <div class="admin-logo-icon">⚙️</div>
          <span>Admin Panel</span>
        </div>
        
        <nav class="admin-nav">
          <a href="{{ route('admin.books.index') }}" class="admin-nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
            <span>📚</span>
            <span>Quản lý Sách</span>
          </a>
          <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span>👤</span>
            <span>Quản lý Users</span>
          </a>
          <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf 
            <button type="submit" class="admin-logout-btn">
              <span>🚪</span>
              <span>Đăng xuất</span>
            </button>
          </form>
        </nav>
      </div>
    </header>

    <main class="admin-main">
      @if(session('success'))
        <div class="admin-alert admin-alert-success">
          ✅ {{ session('success') }}
        </div>
      @endif
      
      @if(session('error'))
        <div class="admin-alert admin-alert-error">
          ❌ {{ session('error') }}
        </div>
      @endif
      
      @yield('content')
    </main>
  </body>
</html>


