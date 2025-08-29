// Admin Questions Management JavaScript
let questionCount = 2; // Bắt đầu từ 2 vì đã có sẵn 2 câu hỏi

// Question types configuration
const questionTypes = {
    single: {
        name: 'Trắc nghiệm',
        icon: '❓',
        color: 'var(--admin-primary)',
        fields: ['id', 'text', 'opt_a', 'opt_b', 'opt_c', 'opt_d', 'correct', 'explain']
    },
    order: {
        name: 'Sắp xếp',
        icon: '🔢',
        color: 'var(--admin-secondary)',
        fields: ['id', 'text', 'items', 'answer']
    },
    match: {
        name: 'Nối từ',
        icon: '🔗',
        color: 'var(--admin-warning)',
        fields: ['id', 'text', 'left_items', 'right_items', 'matches']
    },
    fill: {
        name: 'Điền từ',
        icon: '✏️',
        color: 'var(--admin-danger)',
        fields: ['id', 'text', 'blanks', 'answers']
    }
};

// Initialize questions management
document.addEventListener('DOMContentLoaded', function() {
    initQuestionManagement();
});

function initQuestionManagement() {
    const addButton = document.getElementById('addQuestionBtn');
    if (addButton) {
        addButton.addEventListener('click', addNewQuestion);
    }
    
    // Add change listeners for existing question types
    document.querySelectorAll('.question-type-select').forEach(select => {
        select.addEventListener('change', function() {
            updateQuestionFields(this);
        });
    });
    
    // Update existing question numbering
    updateQuestionNumbers();
}

function addNewQuestion() {
    const container = document.getElementById('questionsContainer');
    const questionHtml = createQuestionHTML(questionCount, 'single');
    
    const questionElement = document.createElement('div');
    questionElement.className = 'question-card';
    questionElement.innerHTML = questionHtml;
    
    container.appendChild(questionElement);
    
    // Add event listeners for the new question
    const newSelect = questionElement.querySelector('.question-type-select');
    newSelect.addEventListener('change', function() {
        updateQuestionFields(this);
    });
    
    const deleteBtn = questionElement.querySelector('.delete-question-btn');
    deleteBtn.addEventListener('click', function() {
        deleteQuestion(questionElement);
    });
    
    questionCount++;
    updateQuestionNumbers();
    
    // Smooth scroll to new question
    questionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Show success message
    showNotification('✅ Đã thêm câu hỏi mới', 'success');
}

function createQuestionHTML(index, type = 'single') {
    const typeConfig = questionTypes[type];
    
    return `
        <div class="question-header">
            <div class="question-title">
                <span class="question-icon" style="background: ${typeConfig.color}">${typeConfig.icon}</span>
                <h4>Câu hỏi <span class="question-number">${index + 1}</span></h4>
                <span class="question-type-badge" style="background: ${typeConfig.color}20; color: ${typeConfig.color}">
                    ${typeConfig.name}
                </span>
            </div>
            <div class="question-actions">
                <select class="question-type-select admin-form-select" name="q[${index}][type]" onchange="updateQuestionFields(this)">
                    <option value="single" ${type === 'single' ? 'selected' : ''}>❓ Trắc nghiệm</option>
                    <option value="order" ${type === 'order' ? 'selected' : ''}>🔢 Sắp xếp</option>
                    <option value="match" ${type === 'match' ? 'selected' : ''}>🔗 Nối từ</option>
                    <option value="fill" ${type === 'fill' ? 'selected' : ''}>✏️ Điền từ</option>
                </select>
                ${index >= 2 ? `
                    <button type="button" class="admin-btn admin-btn-danger delete-question-btn" onclick="deleteQuestion(this.closest('.question-card'))">
                        <span>🗑️</span>
                    </button>
                ` : ''}
            </div>
        </div>
        <div class="question-fields" data-question-index="${index}">
            ${generateQuestionFields(index, type)}
        </div>
    `;
}

function generateQuestionFields(index, type) {
    let html = `
        <div class="admin-form-group">
            <label class="admin-form-label">ID Câu hỏi *</label>
            <input type="text" name="q[${index}][id]" class="admin-form-input" 
                   placeholder="vd: q${index + 1}" required>
            <small>Mã định danh duy nhất cho câu hỏi</small>
        </div>
        
        <div class="admin-form-group">
            <label class="admin-form-label">Nội dung câu hỏi *</label>
            <textarea name="q[${index}][text]" class="admin-form-textarea" 
                      placeholder="Nhập nội dung câu hỏi..." required></textarea>
        </div>
    `;
    
    switch(type) {
        case 'single':
            html += `
                <div class="options-grid">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Phương án A *</label>
                        <input type="text" name="q[${index}][opt_a]" class="admin-form-input" 
                               placeholder="Phương án A" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Phương án B *</label>
                        <input type="text" name="q[${index}][opt_b]" class="admin-form-input" 
                               placeholder="Phương án B" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Phương án C *</label>
                        <input type="text" name="q[${index}][opt_c]" class="admin-form-input" 
                               placeholder="Phương án C" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Phương án D *</label>
                        <input type="text" name="q[${index}][opt_d]" class="admin-form-input" 
                               placeholder="Phương án D" required>
                    </div>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Đáp án đúng *</label>
                    <select name="q[${index}][correct]" class="admin-form-select" required>
                        <option value="">Chọn đáp án đúng</option>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Giải thích</label>
                    <textarea name="q[${index}][explain]" class="admin-form-textarea" 
                              placeholder="Giải thích đáp án (tùy chọn)"></textarea>
                </div>
            `;
            break;
            
        case 'order':
            html += `
                <div class="admin-form-group">
                    <label class="admin-form-label">Các bước (phân cách bởi dấu phẩy) *</label>
                    <textarea name="q[${index}][items]" class="admin-form-textarea" 
                              placeholder="Bước 1, Bước 2, Bước 3, ..." required></textarea>
                    <small>Nhập các bước sẽ được xáo trộn để học sinh sắp xếp</small>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Thứ tự đúng (phân cách bởi dấu phẩy) *</label>
                    <textarea name="q[${index}][answer]" class="admin-form-textarea" 
                              placeholder="Bước đúng 1, Bước đúng 2, ..." required></textarea>
                    <small>Thứ tự đúng của các bước</small>
                </div>
            `;
            break;
            
        case 'match':
            html += `
                <div class="options-grid">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Cột trái (phân cách bởi dấu phẩy) *</label>
                        <textarea name="q[${index}][left_items]" class="admin-form-textarea" 
                                  placeholder="Từ 1, Từ 2, Từ 3, ..." required></textarea>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Cột phải (phân cách bởi dấu phẩy) *</label>
                        <textarea name="q[${index}][right_items]" class="admin-form-textarea" 
                                  placeholder="Nghĩa 1, Nghĩa 2, Nghĩa 3, ..." required></textarea>
                    </div>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Cặp đúng (phân cách bởi dấu phẩy) *</label>
                    <textarea name="q[${index}][matches]" class="admin-form-textarea" 
                              placeholder="Từ 1:Nghĩa 1, Từ 2:Nghĩa 2, ..." required></textarea>
                    <small>Định dạng: item_trái:item_phải, phân cách bởi dấu phẩy</small>
                </div>
            `;
            break;
            
        case 'fill':
            html += `
                <div class="admin-form-group">
                    <label class="admin-form-label">Câu có chỗ trống (dùng ___ cho chỗ trống) *</label>
                    <textarea name="q[${index}][blanks]" class="admin-form-textarea" 
                              placeholder="Hôm nay tôi đi ___ và mua ___" required></textarea>
                    <small>Sử dụng ___ để đánh dấu vị trí cần điền</small>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Đáp án (phân cách bởi dấu phẩy) *</label>
                    <textarea name="q[${index}][answers]" class="admin-form-textarea" 
                              placeholder="chợ, rau" required></textarea>
                    <small>Đáp án cho từng chỗ trống, theo thứ tự</small>
                </div>
            `;
            break;
    }
    
    return html;
}

function updateQuestionFields(selectElement) {
    const questionCard = selectElement.closest('.question-card');
    const fieldsContainer = questionCard.querySelector('.question-fields');
    const index = parseInt(fieldsContainer.dataset.questionIndex);
    const newType = selectElement.value;
    
    // Update question header
    const typeConfig = questionTypes[newType];
    const icon = questionCard.querySelector('.question-icon');
    const badge = questionCard.querySelector('.question-type-badge');
    
    icon.textContent = typeConfig.icon;
    icon.style.background = typeConfig.color;
    badge.textContent = typeConfig.name;
    badge.style.background = typeConfig.color + '20';
    badge.style.color = typeConfig.color;
    
    // Update fields
    fieldsContainer.innerHTML = generateQuestionFields(index, newType);
    
    showNotification('🔄 Đã cập nhật loại câu hỏi', 'success');
}

function deleteQuestion(questionCard) {
    if (document.querySelectorAll('.question-card').length <= 1) {
        showNotification('⚠️ Phải có ít nhất 1 câu hỏi', 'warning');
        return;
    }
    
    if (confirm('Bạn có chắc muốn xóa câu hỏi này?')) {
        questionCard.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            questionCard.remove();
            updateQuestionNumbers();
            showNotification('🗑️ Đã xóa câu hỏi', 'success');
        }, 300);
    }
}

function updateQuestionNumbers() {
    document.querySelectorAll('.question-card').forEach((card, index) => {
        const numberSpan = card.querySelector('.question-number');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
        
        // Update field names to maintain correct indexing
        const fieldsContainer = card.querySelector('.question-fields');
        if (fieldsContainer) {
            fieldsContainer.dataset.questionIndex = index;
            
            // Update all input/select/textarea names
            fieldsContainer.querySelectorAll('[name^="q["]').forEach(input => {
                const oldName = input.name;
                const fieldName = oldName.match(/\[([^\]]+)\]$/)[1];
                input.name = `q[${index}][${fieldName}]`;
            });
        }
    });
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.admin-notification').forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `admin-notification admin-notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Show animation
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// CSS Animation for slide out
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
            max-height: 1000px;
        }
        to {
            opacity: 0;
            transform: translateX(100%);
            max-height: 0;
        }
    }
    
    .admin-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .admin-notification.show {
        transform: translateX(0);
    }
    
    .admin-notification-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .admin-notification-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .admin-notification-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .admin-notification-info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }
`;
document.head.appendChild(style);
