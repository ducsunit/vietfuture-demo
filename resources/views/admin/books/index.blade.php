@extends('admin.layout')
@section('content')
<div class="foot" style="justify-content:space-between;">
  <h2>Quản lý Sách</h2>
  <a class="btn btn-primary" href="{{ route('admin.books.create') }}">+ Thêm sách</a>
</div>
<div class="grid">
  @foreach($books as $b)
    <div class="card">
      <b>{{ $b->title }}</b>
      <div class="muted">UID: {{ $b->book_uid }}</div>
      <div class="foot">
        <a class="btn btn-ghost" href="{{ route('admin.books.edit', $b->id) }}">Sửa</a>
        <form method="POST" action="{{ route('admin.books.delete', $b->id) }}" onsubmit="return confirm('Xoá?')">@csrf @method('DELETE') <button class="btn btn-ghost" type="submit">Xoá</button></form>
      </div>
    </div>
  @endforeach
</div>
{{ $books->links() }}
@endsection


