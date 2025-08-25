@extends('admin.layout')
@section('title', 'Thêm sách mới')
@section('content')

<div class="admin-page-header">
  <h1 class="admin-page-title">
    <span>➕</span>
    Thêm sách mới
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
    <span>✅</span>
    <span>Hoàn thành</span>
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
  <form method="POST" action="{{ route('admin.books.store') }}" id="bookForm">
    @csrf
    
    <!-- Basic Information -->
    <div class="lesson-info-card">
      <h3>📚 Thông tin sách</h3>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <div class="admin-form-group">
          <label class="admin-form-label" for="book_uid">UID Sách</label>
          <input type="text" id="book_uid" name="book_uid" class="admin-form-input" 
                 placeholder="vd: phong-chong-duoi-nuoc" value="{{ old('book_uid') }}" required>
          <small>Mã định danh duy nhất cho sách (chỉ chữ cái, số, dấu gạch ngang)</small>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label" for="title">Tiêu đề sách</label>
          <input type="text" id="title" name="title" class="admin-form-input" 
                 placeholder="vd: Phòng chống đuối nước" value="{{ old('title') }}" required>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label" for="lesson_id">ID Bài học</label>
          <input type="text" id="lesson_id" name="lesson_id" class="admin-form-input" 
                 placeholder="vd: an-toan-nuoc" value="{{ old('lesson_id') }}">
          <small>ID cho bài học cụ thể trong sách</small>
        </div>
        
        <div class="admin-form-group">
          <label class="admin-form-label" for="lesson_title">Tiêu đề bài học</label>
          <input type="text" id="lesson_title" name="lesson_title" class="admin-form-input" 
                 placeholder="vd: An toàn dưới nước" value="{{ old('lesson_title') }}">
        </div>
      </div>
    </div>
    
    <!-- Questions Section -->
    <div class="questions-section">
      <div class="questions-header">
        <div class="questions-title">
          <span>❓</span>
          <span>Quản lý câu hỏi</span>
        </div>
        <button type="button" id="addQuestionBtn" class="add-question-btn">
          <span>➕</span>
          <span>Thêm câu hỏi</span>
        </button>
      </div>
      
      <div id="questionsContainer" class="questions-container">
        <!-- Question 1 -->
        <div class="question-card" data-type="single">
          <div class="question-header">
            <div class="question-title">
              <span class="question-icon" style="background: var(--admin-primary)">❓</span>
              <h4>Câu hỏi <span class="question-number">1</span></h4>
              <span class="question-type-badge" style="background: var(--admin-primary)20; color: var(--admin-primary)">
                Trắc nghiệm
              </span>
            </div>
            <div class="question-actions">
              <select class="question-type-select admin-form-select" name="q[0][type]">
                <option value="single" selected>❓ Trắc nghiệm</option>
                <option value="order">🔢 Sắp xếp</option>
                <option value="match">🔗 Nối từ</option>
                <option value="fill">✏️ Điền từ</option>
              </select>
            </div>
          </div>
          <div class="question-fields" data-question-index="0">
            <div class="admin-form-group">
              <label class="admin-form-label">ID Câu hỏi</label>
              <input type="text" name="q[0][id]" class="admin-form-input" 
                     placeholder="vd: q1" value="{{ old('q.0.id') }}" required>
              <small>Mã định danh duy nhất cho câu hỏi</small>
            </div>
            
            <div class="admin-form-group">
              <label class="admin-form-label">Nội dung câu hỏi</label>
              <textarea name="q[0][text]" class="admin-form-textarea" 
                        placeholder="Nhập nội dung câu hỏi..." required>{{ old('q.0.text') }}</textarea>
            </div>
            
            <div class="options-grid">
              <div class="admin-form-group">
                <label class="admin-form-label">Phương án A</label>
                <input type="text" name="q[0][opt_a]" class="admin-form-input" 
                       placeholder="Phương án A" value="{{ old('q.0.opt_a') }}" required>
              </div>
              <div class="admin-form-group">
                <label class="admin-form-label">Phương án B</label>
                <input type="text" name="q[0][opt_b]" class="admin-form-input" 
                       placeholder="Phương án B" value="{{ old('q.0.opt_b') }}" required>
              </div>
              <div class="admin-form-group">
                <label class="admin-form-label">Phương án C</label>
                <input type="text" name="q[0][opt_c]" class="admin-form-input" 
                       placeholder="Phương án C" value="{{ old('q.0.opt_c') }}" required>
              </div>
              <div class="admin-form-group">
                <label class="admin-form-label">Phương án D</label>
                <input type="text" name="q[0][opt_d]" class="admin-form-input" 
                       placeholder="Phương án D" value="{{ old('q.0.opt_d') }}" required>
              </div>
            </div>
            
            <div class="admin-form-group">
              <label class="admin-form-label">Đáp án đúng</label>
              <select name="q[0][correct]" class="admin-form-select" required>
                <option value="">Chọn đáp án đúng</option>
                <option value="a" {{ old('q.0.correct') == 'a' ? 'selected' : '' }}>A</option>
                <option value="b" {{ old('q.0.correct') == 'b' ? 'selected' : '' }}>B</option>
                <option value="c" {{ old('q.0.correct') == 'c' ? 'selected' : '' }}>C</option>
                <option value="d" {{ old('q.0.correct') == 'd' ? 'selected' : '' }}>D</option>
              </select>
            </div>
            
            <div class="admin-form-group">
              <label class="admin-form-label">Giải thích</label>
              <textarea name="q[0][explain]" class="admin-form-textarea" 
                        placeholder="Giải thích đáp án (tùy chọn)">{{ old('q.0.explain') }}</textarea>
            </div>
          </div>
        </div>
        
        <!-- Question 2 -->
        <div class="question-card" data-type="order">
          <div class="question-header">
            <div class="question-title">
              <span class="question-icon" style="background: var(--admin-secondary)">🔢</span>
              <h4>Câu hỏi <span class="question-number">2</span></h4>
              <span class="question-type-badge" style="background: var(--admin-secondary)20; color: var(--admin-secondary)">
                Sắp xếp
              </span>
            </div>
            <div class="question-actions">
              <select class="question-type-select admin-form-select" name="q[1][type]">
                <option value="single">❓ Trắc nghiệm</option>
                <option value="order" selected>🔢 Sắp xếp</option>
                <option value="match">🔗 Nối từ</option>
                <option value="fill">✏️ Điền từ</option>
              </select>
            </div>
          </div>
          <div class="question-fields" data-question-index="1">
            <div class="admin-form-group">
              <label class="admin-form-label">ID Câu hỏi</label>
              <input type="text" name="q[1][id]" class="admin-form-input" 
                     placeholder="vd: q2" value="{{ old('q.1.id') }}" required>
              <small>Mã định danh duy nhất cho câu hỏi</small>
            </div>
            
            <div class="admin-form-group">
              <label class="admin-form-label">Nội dung câu hỏi</label>
              <textarea name="q[1][text]" class="admin-form-textarea" 
                        placeholder="Nhập câu hỏi sắp xếp..." required>{{ old('q.1.text') }}</textarea>
            </div>
            
            <div class="admin-form-group">
              <label class="admin-form-label">Các bước (phân cách bởi dấu phẩy)</label>
              <textarea name="q[1][items]" class="admin-form-textarea" 
                        placeholder="Bước 1, Bước 2, Bước 3, ..." required>{{ old('q.1.items') }}</textarea>
              <small>Nhập các bước sẽ được xáo trộn để học sinh sắp xếp</small>
            </div>
            
            <div class="admin-form-group">
              <label class="admin-form-label">Thứ tự đúng (phân cách bởi dấu phẩy)</label>
              <textarea name="q[1][answer]" class="admin-form-textarea" 
                        placeholder="Bước đúng 1, Bước đúng 2, ..." required>{{ old('q.1.answer') }}</textarea>
              <small>Thứ tự đúng của các bước</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--admin-border);">
      <a href="{{ route('admin.books.index') }}" class="admin-btn admin-btn-secondary">
        <span>❌</span>
        <span>Hủy bỏ</span>
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <span>💾</span>
        <span>Lưu sách</span>
      </button>
    </div>
  </form>
</div>

<script src="{{ asset('js/admin-questions.js') }}"></script>

@endsection


