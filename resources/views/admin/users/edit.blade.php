@extends('admin.layout')
@section('content')
<h2>Sửa user</h2>
@if ($errors->any())
  <div class="notice" style="color:#dc2626;">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ route('admin.users.update', $user->id) }}" style="display:grid; gap:12px;">
  @csrf
  @method('PUT')
  <input name="username" value="{{ $user->username }}" placeholder="Tên đăng nhập" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <input type="password" name="password" placeholder="Để trống nếu không đổi mật khẩu" style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <input name="age" value="{{ $user->age }}" placeholder="Tuổi (VD: 8-10)" style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <div class="foot">
    <a class="btn btn-ghost" href="{{ route('admin.users.index') }}">Hủy</a>
    <button class="btn btn-primary" type="submit">Cập nhật</button>
  </div>
</form>
@endsection


