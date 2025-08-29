@extends('admin.layout')
@section('title', 'Quản lý Users')
@section('content')

<div class="admin-page-header">
  <h1 class="admin-page-title">
    <span>👤</span>
    Quản lý Users
  </h1>
  <div class="admin-page-actions">
    <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary">
      <span>➕</span>
      <span>Thêm user mới</span>
    </a>
  </div>
</div>

<!-- Statistics Cards -->
<div class="admin-stats-grid">
  <div class="admin-stat-card users">
    <div class="admin-stat-header">
      <div class="admin-stat-title">Tổng số users</div>
      <div class="admin-stat-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">👤</div>
    </div>
    <div class="admin-stat-value">{{ $users->total() }}</div>
    <div class="admin-stat-change positive">
      <span>👥</span>
      <span>Quản lý người dùng</span>
    </div>
  </div>
  
  <div class="admin-stat-card users">
    <div class="admin-stat-header">
      <div class="admin-stat-title">Admin Users</div>
      <div class="admin-stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">⚙️</div>
    </div>
    <div class="admin-stat-value">{{ $users->where('role', 1)->count() }}</div>
    <div class="admin-stat-change">
      <span>🔧</span>
      <span>Quản trị viên</span>
    </div>
  </div>
  
  <div class="admin-stat-card users">
    <div class="admin-stat-header">
      <div class="admin-stat-title">Regular Users</div>
      <div class="admin-stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;">👨‍👩‍👧‍👦</div>
    </div>
    <div class="admin-stat-value">{{ $users->where('role', 0)->count() }}</div>
    <div class="admin-stat-change">
      <span>👨‍👩‍👧‍👦</span>
      <span>Người dùng thường</span>
    </div>
  </div>
</div>

@if($users->count() > 0)
<div class="admin-cards-grid">
  @foreach($users as $user)
    <div class="admin-card">
      <h3 class="admin-card-title">
        {{ $user->username }}
        @if($user->role == 1)
          <span style="background: #fbbf24; color: white; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; margin-left: 0.5rem;">ADMIN</span>
        @endif
      </h3>
      
      @if($user->display_name)
        <div class="admin-card-meta">
          <span>📝</span>
          <span>Tên hiển thị: {{ $user->display_name }}</span>
        </div>
      @endif
      
      <div class="admin-card-meta">
        <span>🎂</span>
        <span>Tuổi: {{ $user->age ?? 'Chưa cập nhật' }}</span>
      </div>
      
      <div class="admin-card-meta">
        <span>🏆</span>
        <span>Điểm: {{ $user->point ?? 0 }} điểm</span>
      </div>
      
      <div class="admin-card-meta">
        <span>📅</span>
        <span>Tham gia: {{ $user->created_at->format('d/m/Y H:i') }}</span>
      </div>
      
      <div class="admin-card-actions">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="admin-btn admin-btn-secondary">
          <span>✏️</span>
          <span>Chỉnh sửa</span>
        </a>
        @if($user->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" style="display: inline;">
          @csrf 
          @method('DELETE')
          <button type="submit" class="admin-btn admin-btn-danger" onclick="return confirm('Bạn có chắc muốn xóa user này?')">
            <span>🗑️</span>
            <span>Xóa</span>
          </button>
        </form>
        @else
        <span class="admin-btn admin-btn-secondary" style="opacity: 0.5; cursor: not-allowed;">
          <span>🚫</span>
          <span>Không thể xóa</span>
        </span>
        @endif
      </div>
    </div>
  @endforeach
</div>

<div class="admin-pagination">
  {{ $users->links() }}
</div>
@else
<div class="admin-card" style="text-align: center; padding: 3rem;">
  <div style="font-size: 4rem; margin-bottom: 1rem;">👤</div>
  <h3 style="margin-bottom: 0.5rem;">Chưa có user nào</h3>
  <p style="color: var(--admin-gray); margin-bottom: 2rem;">Hãy tạo user đầu tiên để bắt đầu quản lý.</p>
  <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary">
    <span>➕</span>
    <span>Tạo user đầu tiên</span>
  </a>
</div>
@endif

@endsection


