@extends('admin.layout')
@section('content')
<h2>Thêm sách</h2>
@if ($errors->any())
  <div class="notice" style="color:#dc2626;">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ route('admin.books.store') }}" style="display:grid; gap:12px;">
  @csrf
  <input name="book_uid" placeholder="UID sách" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <input name="title" placeholder="Tiêu đề" required style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
  <div class="card">
    <h3>Thông tin bài học</h3>
    <input name="lesson_id" placeholder="Lesson ID (vd: an-toan-nuoc)" style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
    <input name="lesson_title" placeholder="Tiêu đề bài" style="padding:10px; border:1px solid #e2e8f0; border-radius:8px;" />
    <div class="grid">
      <div class="card">
        <b>Câu hỏi 1 (single)</b>
        <input name="q[0][id]" placeholder="ID câu (vd: q1)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][type]" value="single" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][text]" placeholder="Nội dung câu hỏi" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_a]" placeholder="Phương án A" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_b]" placeholder="Phương án B" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_c]" placeholder="Phương án C" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][opt_d]" placeholder="Phương án D" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][correct]" placeholder="Đáp án đúng (a/b/c/d)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[0][explain]" placeholder="Giải thích" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
      </div>
      <div class="card">
        <b>Câu hỏi 2 (order)</b>
        <input name="q[1][id]" placeholder="ID câu (vd: q2)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][type]" value="order" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][text]" placeholder="Nội dung câu hỏi" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][items]" placeholder="Các bước (cách nhau dấu ,)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
        <input name="q[1][answer]" placeholder="Đáp án đúng (cách nhau dấu ,)" style="padding:8px; border:1px solid #e2e8f0; border-radius:8px;" />
      </div>
    </div>
  </div>
  <div class="foot">
    <a class="btn btn-ghost" href="{{ route('admin.books.index') }}">Hủy</a>
    <button class="btn btn-primary" type="submit">Lưu</button>
  </div>
</form>
@endsection


