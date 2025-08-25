@extends('admin.layout')
@section('content')
<h2>Sửa sách</h2>
@if ($errors->any())
  <div class="notice" style="color:#dc2626;">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ route('admin.books.update', $book->id) }}" style="display:grid; gap:12px;">
  @csrf
  @method('PUT')
  <input name="book_uid" value="{{ $book->book_uid }}" placeholder="UID sách" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <input name="title" value="{{ $book->title }}" placeholder="Tiêu đề" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <div class="card">
    <h3>Thông tin bài học</h3>
    @php($parsed = $book->content ? json_decode($book->content, true) : null)
    @php($lesson = $parsed['lessons'][0] ?? null)
    <input name="lesson_id" value="{{ $lesson['id'] ?? '' }}" placeholder="Lesson ID" style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
    <input name="lesson_title" value="{{ $lesson['title'] ?? '' }}" placeholder="Tiêu đề bài" style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
    @php($q0 = ($lesson['questions'][0] ?? null))
    <div class="grid">
      <div class="card">
        <b>Câu hỏi 1 (single)</b>
        <input name="q[0][id]" value="{{ $q0['id'] ?? '' }}" placeholder="ID câu" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][type]" value="{{ $q0['type'] ?? 'single' }}" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][text]" value="{{ $q0['text'] ?? '' }}" placeholder="Nội dung câu hỏi" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        @php($opts = $q0['options'] ?? [])
        <input name="q[0][opt_a]" value="{{ ($opts[0]['text'] ?? '') }}" placeholder="Phương án A" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_b]" value="{{ ($opts[1]['text'] ?? '') }}" placeholder="Phương án B" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_c]" value="{{ ($opts[2]['text'] ?? '') }}" placeholder="Phương án C" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_d]" value="{{ ($opts[3]['text'] ?? '') }}" placeholder="Phương án D" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        @php($correctOpt = collect($opts)->firstWhere('correct', true))
        <input name="q[0][correct]" value="{{ $correctOpt ? $correctOpt['id'] : '' }}" placeholder="Đáp án đúng (a/b/c/d)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][explain]" value="{{ $q0['explain'] ?? '' }}" placeholder="Giải thích" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
      </div>
      @php($q1 = ($lesson['questions'][1] ?? null))
      <div class="card">
        <b>Câu hỏi 2 (order)</b>
        <input name="q[1][id]" value="{{ $q1['id'] ?? '' }}" placeholder="ID câu" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][type]" value="{{ $q1['type'] ?? 'order' }}" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][text]" value="{{ $q1['text'] ?? '' }}" placeholder="Nội dung câu hỏi" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][items]" value="{{ isset($q1['items']) ? implode(',', $q1['items']) : '' }}" placeholder="Các bước (cách nhau dấu ,)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][answer]" value="{{ isset($q1['answer']) ? implode(',', $q1['answer']) : '' }}" placeholder="Đáp án đúng (cách nhau dấu ,)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
      </div>
    </div>
  </div>
  <div class="foot">
    <a class="btn btn-ghost" href="{{ route('admin.books.index') }}">Hủy</a>
    <button class="btn btn-primary" type="submit">Cập nhật</button>
  </div>
</form>
@endsection


