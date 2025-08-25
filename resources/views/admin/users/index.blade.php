@extends('admin.layout')
@section('content')
<div class="foot" style="justify-content:space-between;">
  <h2>Quản lý Users</h2>
  <a class="btn btn-primary" href="{{ route('admin.users.create') }}">+ Thêm user</a>
</div>
<div class="grid">
  @foreach($users as $u)
    <div class="card">
      <b>{{ $u->username }}</b>
      <div class="muted">Tuổi: {{ $u->age }}</div>
      <div class="foot">
        <a class="btn btn-ghost" href="{{ route('admin.users.edit', $u->id) }}">Sửa</a>
        <form method="POST" action="{{ route('admin.users.delete', $u->id) }}" onsubmit="return confirm('Xoá?')">@csrf @method('DELETE') <button class="btn btn-ghost" type="submit">Xoá</button></form>
      </div>
    </div>
  @endforeach
</div>
{{ $users->links() }}
@endsection


