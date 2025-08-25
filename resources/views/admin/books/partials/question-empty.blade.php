<!-- Default single question if no questions exist -->
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
             placeholder="vd: q1" required>
      <small>Mã định danh duy nhất cho câu hỏi</small>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label">Nội dung câu hỏi</label>
      <textarea name="q[0][text]" class="admin-form-textarea" 
                placeholder="Nhập nội dung câu hỏi..." required></textarea>
    </div>
    
    <div class="options-grid">
      <div class="admin-form-group">
        <label class="admin-form-label">Phương án A</label>
        <input type="text" name="q[0][opt_a]" class="admin-form-input" 
               placeholder="Phương án A" required>
      </div>
      <div class="admin-form-group">
        <label class="admin-form-label">Phương án B</label>
        <input type="text" name="q[0][opt_b]" class="admin-form-input" 
               placeholder="Phương án B" required>
      </div>
      <div class="admin-form-group">
        <label class="admin-form-label">Phương án C</label>
        <input type="text" name="q[0][opt_c]" class="admin-form-input" 
               placeholder="Phương án C" required>
      </div>
      <div class="admin-form-group">
        <label class="admin-form-label">Phương án D</label>
        <input type="text" name="q[0][opt_d]" class="admin-form-input" 
               placeholder="Phương án D" required>
      </div>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label">Đáp án đúng</label>
      <select name="q[0][correct]" class="admin-form-select" required>
        <option value="">Chọn đáp án đúng</option>
        <option value="a">A</option>
        <option value="b">B</option>
        <option value="c">C</option>
        <option value="d">D</option>
      </select>
    </div>
    
    <div class="admin-form-group">
      <label class="admin-form-label">Giải thích</label>
      <textarea name="q[0][explain]" class="admin-form-textarea" 
                placeholder="Giải thích đáp án (tùy chọn)"></textarea>
    </div>
  </div>
</div>
