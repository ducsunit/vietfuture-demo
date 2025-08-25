@php
  $qType = $question['type'] ?? 'single';
  $typeConfig = [
    'single' => ['name' => 'Trắc nghiệm', 'icon' => '❓', 'color' => 'var(--admin-primary)'],
    'order' => ['name' => 'Sắp xếp', 'icon' => '🔢', 'color' => 'var(--admin-secondary)'],
    'match' => ['name' => 'Nối từ', 'icon' => '🔗', 'color' => 'var(--admin-warning)'],
    'fill' => ['name' => 'Điền từ', 'icon' => '✏️', 'color' => 'var(--admin-danger)']
  ];
  $config = $typeConfig[$qType] ?? $typeConfig['single'];
@endphp

<div class="question-card" data-type="{{ $qType }}">
  <div class="question-header">
    <div class="question-title">
      <span class="question-icon" style="background: {{ $config['color'] }}">{{ $config['icon'] }}</span>
      <h4>Câu hỏi <span class="question-number">{{ $index + 1 }}</span></h4>
      <span class="question-type-badge" style="background: {{ $config['color'] }}20; color: {{ $config['color'] }}">
        {{ $config['name'] }}
      </span>
    </div>
    <div class="question-actions">
      <select class="question-type-select admin-form-select" name="q[{{ $index }}][type]">
        <option value="single" {{ $qType === 'single' ? 'selected' : '' }}>❓ Trắc nghiệm</option>
        <option value="order" {{ $qType === 'order' ? 'selected' : '' }}>🔢 Sắp xếp</option>
        <option value="match" {{ $qType === 'match' ? 'selected' : '' }}>🔗 Nối từ</option>
        <option value="fill" {{ $qType === 'fill' ? 'selected' : '' }}>✏️ Điền từ</option>
      </select>
      @if($index >= 2)
        <button type="button" class="admin-btn admin-btn-danger delete-question-btn">
          <span>🗑️</span>
        </button>
      @endif
    </div>
  </div>
  
  <div class="question-fields" data-question-index="{{ $index }}">
    <div class="admin-form-group">
      <label class="admin-form-label">ID Câu hỏi</label>
      <input type="text" name="q[{{ $index }}][id]" class="admin-form-input" 
             placeholder="vd: q{{ $index + 1 }}" 
             value="{{ old('q.'.$index.'.id', $question['id'] ?? '') }}" required>
      <small>Mã định danh duy nhất cho câu hỏi</small>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label">Nội dung câu hỏi</label>
      <textarea name="q[{{ $index }}][text]" class="admin-form-textarea" 
                placeholder="Nhập nội dung câu hỏi..." required>{{ old('q.'.$index.'.text', $question['text'] ?? '') }}</textarea>
    </div>
    
    @if($qType === 'single')
      @php($opts = $question['options'] ?? [])
      <div class="options-grid">
        <div class="admin-form-group">
          <label class="admin-form-label">Phương án A</label>
          <input type="text" name="q[{{ $index }}][opt_a]" class="admin-form-input" 
                 placeholder="Phương án A" 
                 value="{{ old('q.'.$index.'.opt_a', $opts[0]['text'] ?? '') }}" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label">Phương án B</label>
          <input type="text" name="q[{{ $index }}][opt_b]" class="admin-form-input" 
                 placeholder="Phương án B" 
                 value="{{ old('q.'.$index.'.opt_b', $opts[1]['text'] ?? '') }}" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label">Phương án C</label>
          <input type="text" name="q[{{ $index }}][opt_c]" class="admin-form-input" 
                 placeholder="Phương án C" 
                 value="{{ old('q.'.$index.'.opt_c', $opts[2]['text'] ?? '') }}" required>
        </div>
        <div class="admin-form-group">
          <label class="admin-form-label">Phương án D</label>
          <input type="text" name="q[{{ $index }}][opt_d]" class="admin-form-input" 
                 placeholder="Phương án D" 
                 value="{{ old('q.'.$index.'.opt_d', $opts[3]['text'] ?? '') }}" required>
        </div>
      </div>
      
      @php($correctOpt = collect($opts)->firstWhere('correct', true))
      <div class="admin-form-group">
        <label class="admin-form-label">Đáp án đúng</label>
        <select name="q[{{ $index }}][correct]" class="admin-form-select" required>
          <option value="">Chọn đáp án đúng</option>
          <option value="a" {{ old('q.'.$index.'.correct', $correctOpt['id'] ?? '') === 'a' ? 'selected' : '' }}>A</option>
          <option value="b" {{ old('q.'.$index.'.correct', $correctOpt['id'] ?? '') === 'b' ? 'selected' : '' }}>B</option>
          <option value="c" {{ old('q.'.$index.'.correct', $correctOpt['id'] ?? '') === 'c' ? 'selected' : '' }}>C</option>
          <option value="d" {{ old('q.'.$index.'.correct', $correctOpt['id'] ?? '') === 'd' ? 'selected' : '' }}>D</option>
        </select>
      </div>
      
      <div class="admin-form-group">
        <label class="admin-form-label">Giải thích</label>
        <textarea name="q[{{ $index }}][explain]" class="admin-form-textarea" 
                  placeholder="Giải thích đáp án (tùy chọn)">{{ old('q.'.$index.'.explain', $question['explain'] ?? '') }}</textarea>
      </div>
    @elseif($qType === 'order')
      <div class="admin-form-group">
        <label class="admin-form-label">Các bước (phân cách bởi dấu phẩy)</label>
        <textarea name="q[{{ $index }}][items]" class="admin-form-textarea" 
                  placeholder="Bước 1, Bước 2, Bước 3, ..." required>{{ old('q.'.$index.'.items', isset($question['items']) ? implode(',', $question['items']) : '') }}</textarea>
        <small>Nhập các bước sẽ được xáo trộn để học sinh sắp xếp</small>
      </div>
      
      <div class="admin-form-group">
        <label class="admin-form-label">Thứ tự đúng (phân cách bởi dấu phẩy)</label>
        <textarea name="q[{{ $index }}][answer]" class="admin-form-textarea" 
                  placeholder="Bước đúng 1, Bước đúng 2, ..." required>{{ old('q.'.$index.'.answer', isset($question['answer']) ? implode(',', $question['answer']) : '') }}</textarea>
        <small>Thứ tự đúng của các bước</small>
      </div>
    @endif
  </div>
</div>
