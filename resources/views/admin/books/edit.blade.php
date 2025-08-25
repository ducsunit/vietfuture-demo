@extends('admin.layout')
@section('title', 'Chỉnh sửa sách')
@section('content')

<div class="admin-page-header">
  <h1 class="admin-page-title">
    <span>✏️</span>
    Chỉnh sửa: {{ $book->title }}
  </h1>
  <div class="admin-page-actions">
    <a href="{{ route('admin.books.index') }}" class="admin-btn admin-btn-secondary">
      <span>◀</span>
      <span>Quay lại</span>
    </a>
  </div>
</div>

<!-- Progress Indicator -->
<div class="form-progress">
  <div class="progress-step completed">
    <span>📋</span>
    <span>Thông tin cơ bản</span>
  </div>
  <div class="progress-step active">
    <span>❓</span>
    <span>Câu hỏi</span>
  </div>
  <div class="progress-step">
    <span>💾</span>
    <span>Cập nhật</span>
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

@php
  $parsed = $book->content ? json_decode($book->content, true) : null;
  $lesson = $parsed['lessons'][0] ?? null;
  $questions = $lesson['questions'] ?? [];
@endphp

<div class="admin-form-container edit-form">
  <form method="POST" action="{{ route('admin.books.update', $book->id) }}" id="bookForm">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div class="lesson-info-card">
      <h3>📚 Thông tin sách</h3>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="book_uid">UID Sách</label>
          <input type="text" id="book_uid" name="book_uid" class="admin-form-input" 
                 value="{{ old('book_uid', $book->book_uid) }}" required>
          <small>Mã định danh duy nhất cho sách</small>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label" for="title">Tiêu đề sách</label>
          <input type="text" id="title" name="title" class="admin-form-input" 
                 value="{{ old('title', $book->title) }}" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label" for="lesson_id">ID Bài học</label>
          <input type="text" id="lesson_id" name="lesson_id" class="admin-form-input" 
                 value="{{ old('lesson_id', $lesson['id'] ?? '') }}">
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label" for="lesson_title">Tiêu đề bài học</label>
          <input type="text" id="lesson_title" name="lesson_title" class="admin-form-input" 
                 value="{{ old('lesson_title', $lesson['title'] ?? '') }}">
        </div>
      </div>
    </div>
    
    <!-- Questions Section -->
    <div class="questions-section">
      <div class="questions-header">
        <div class="questions-title">
          <span>❓</span>
          <span>Quản lý câu hỏi</span>
          <span style="background: var(--admin-primary)20; color: var(--admin-primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">{{ count($questions) }} câu hỏi</span>
        </div>
        <button type="button" id="addQuestionBtn" class="add-question-btn">
          <span>➕</span>
          <span>Thêm câu hỏi</span>
        </button>
      </div>
      
      <div id="questionsContainer" class="questions-container">
        @forelse($questions as $index => $question)
          @include('admin.books.partials.question-edit', ['question' => $question, 'index' => $index])
        @empty
          @include('admin.books.partials.question-empty')
        @endforelse
      </div>
    </div>
    
    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--admin-border);">
      <a href="{{ route('admin.books.index') }}" class="admin-btn admin-btn-secondary">
        <span>❌</span>
        <span>Hủy bỏ</span>
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <span>💾</span>
        <span>Cập nhật sách</span>
      </button>
    </div>
  </form>
</div>

<script>
let questionCount = {{ count($questions) ?: 1 }};
</script>
<script src="{{ asset('js/admin-questions.js') }}"></script>

@endsection
