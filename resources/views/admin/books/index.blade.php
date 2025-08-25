@extends('admin.layout')
@section('title', 'Quản lý Sách')
@section('content')

<div class="admin-page-header">
  <h1 class="admin-page-title">
    <span>📚</span>
    Quản lý Sách
  </h1>
  <div class="admin-page-actions">
    <a href="{{ route('admin.books.create') }}" class="admin-btn admin-btn-primary">
      <span>➕</span>
      <span>Thêm sách mới</span>
    </a>
  </div>
</div>

<!-- Statistics Cards -->
<div class="admin-stats-grid">
  <div class="admin-stat-card books">
    <div class="admin-stat-header">
      <div class="admin-stat-title">Tổng số sách</div>
      <div class="admin-stat-icon" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;">📚</div>
    </div>
    <div class="admin-stat-value">{{ $books->total() }}</div>
    <div class="admin-stat-change positive">
      <span>📈</span>
      <span>Quản lý nội dung học tập</span>
    </div>
  </div>
</div>

@if($books->count() > 0)
<div class="admin-cards-grid">
  @foreach($books as $book)
    <div class="admin-card">
      <h3 class="admin-card-title">{{ $book->title }}</h3>
      <div class="admin-card-meta">
        <span>🔑</span>
        <span>ID: {{ $book->book_uid }}</span>
      </div>
      <div class="admin-card-meta">
        <span>📅</span>
        <span>Tạo: {{ $book->created_at->format('d/m/Y H:i') }}</span>
      </div>
      @if($book->content)
        <div class="admin-card-meta">
          <span>📄</span>
          <span>Có nội dung: {{ strlen($book->content) }} ký tự</span>
        </div>
      @endif
      
      <div class="admin-card-actions">
        <a href="{{ route('admin.books.edit', $book->id) }}" class="admin-btn admin-btn-secondary">
          <span>✏️</span>
          <span>Chỉnh sửa</span>
        </a>
        <form method="POST" action="{{ route('admin.books.delete', $book->id) }}" style="display: inline;">
          @csrf 
          @method('DELETE')
          <button type="submit" class="admin-btn admin-btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sách này?')">
            <span>🗑️</span>
            <span>Xóa</span>
          </button>
        </form>
      </div>
    </div>
  @endforeach
</div>

<div class="admin-pagination">
  {{ $books->links() }}
</div>
@else
<div class="admin-card" style="text-align: center; padding: 3rem;">
  <div style="font-size: 4rem; margin-bottom: 1rem;">📚</div>
  <h3 style="margin-bottom: 0.5rem;">Chưa có sách nào</h3>
  <p style="color: var(--admin-gray); margin-bottom: 2rem;">Hãy tạo sách đầu tiên để bắt đầu quản lý nội dung.</p>
  <a href="{{ route('admin.books.create') }}" class="admin-btn admin-btn-primary">
    <span>➕</span>
    <span>Tạo sách đầu tiên</span>
  </a>
</div>
@endif

@endsection


