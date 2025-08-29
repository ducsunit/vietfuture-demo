@extends('admin.layout')
@section('title', 'Thêm user mới')
@section('content')

<div class="admin-page-header">
  <h1 class="admin-page-title">
    <span>➕</span>
    Thêm user mới
  </h1>
  <div class="admin-page-actions">
    <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
      <span>◀</span>
      <span>Quay lại</span>
    </a>
  </div>
</div>

@if ($errors->any())
  <div class="admin-alert admin-alert-error">
    <strong>❌ Có lỗi xảy ra:</strong>
    <ul style="margin: 0.5rem 0 0 1rem;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="admin-form-container">
  <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    
    <div class="admin-form-group">
      <label class="admin-form-label" for="username">Tên đăng nhập *</label>
      <input type="text" id="username" name="username" class="admin-form-input" 
             placeholder="vd: minhanh123" value="{{ old('username') }}" required>
      <small style="color: var(--admin-gray); font-size: 0.75rem;">Tên đăng nhập phải là duy nhất trong hệ thống</small>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label" for="password">Mật khẩu *</label>
      <input type="password" id="password" name="password" class="admin-form-input" 
             placeholder="Nhập mật khẩu..." required>
      <small style="color: var(--admin-gray); font-size: 0.75rem;">Mật khẩu sẽ được mã hóa tự động</small>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label" for="display_name">Tên hiển thị</label>
      <input type="text" id="display_name" name="display_name" class="admin-form-input" 
             placeholder="vd: Minh Anh" value="{{ old('display_name') }}">
      <small style="color: var(--admin-gray); font-size: 0.75rem;">Tên này sẽ hiển thị trong cộng đồng và quiz</small>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label" for="age">Tuổi</label>
      <input type="text" id="age" name="age" class="admin-form-input" 
             placeholder="vd: 8-10" value="{{ old('age') }}">
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label" for="point">Điểm ban đầu</label>
      <input type="number" id="point" name="point" class="admin-form-input" 
             placeholder="0" value="{{ old('point', 0) }}" min="0">
      <small style="color: var(--admin-gray); font-size: 0.75rem;">Điểm sẽ được tự động cộng thêm khi hoàn thành quiz</small>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label" for="role">Vai trò *</label>
      <select id="role" name="role" class="admin-form-select" required>
        <option value="0" {{ old('role', '0') == '0' ? 'selected' : '' }}>👤 Người dùng thường</option>
        <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>⚙️ Quản trị viên (Admin)</option>
      </select>
      <small style="color: var(--admin-gray); font-size: 0.75rem;">Admin có thể truy cập trang quản lý này</small>
    </div>
    
    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
      <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">
        <span>❌</span>
        <span>Hủy bỏ</span>
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <span>💾</span>
        <span>Lưu user</span>
      </button>
    </div>
  </form>
</div>

@endsection


