<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>✨ Đăng Ký - VietFuture</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Comic+Neue:wght@300;400;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    
    <style>
      /* Register Page Specific Styles */
      body {
        margin: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        font-family: 'Fredoka', 'Comic Neue', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        overflow: hidden;
      }
      
      /* Animated Background Elements */
      body::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: 
          radial-gradient(circle at 25% 25%, rgba(168, 237, 234, 0.4) 0%, transparent 50%),
          radial-gradient(circle at 75% 75%, rgba(254, 214, 227, 0.4) 0%, transparent 50%),
          radial-gradient(circle at 50% 50%, rgba(196, 181, 253, 0.3) 0%, transparent 50%);
        animation: float 25s ease-in-out infinite;
      }
      
      @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(-30px, 30px) rotate(120deg); }
        66% { transform: translate(20px, -20px) rotate(240deg); }
      }
      
      /* Register Container */
      .register-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
        padding: 0;
      }
      
      /* Main Register Card */
      .register-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 
          0 25px 50px -12px rgba(0, 0, 0, 0.25),
          0 0 0 1px rgba(255, 255, 255, 0.05);
        transform: translateY(0);
        transition: all 0.3s ease;
        animation: slideUp 0.6s ease-out;
      }
      
      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      .register-card:hover {
        transform: translateY(-5px);
        box-shadow: 
          0 35px 60px -12px rgba(0, 0, 0, 0.3),
          0 0 0 1px rgba(255, 255, 255, 0.1);
      }
      
      /* Header Section */
      .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
      }
      
      .register-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.75rem;
        box-shadow: 0 10px 25px rgba(168, 237, 234, 0.4);
        animation: bounce 2s ease-in-out infinite;
      }
      
      @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
      }
      
      .register-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 0.5rem;
        letter-spacing: -0.02em;
      }
      
      .register-subtitle {
        font-size: 1rem;
        color: #64748b;
        margin: 0;
        font-weight: 400;
      }
      
      /* Alert Messages */
      .register-alert {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: fadeIn 0.5s ease-out;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
      }
      
      .register-alert.error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #f87171;
      }
      
      /* Form Styling */
      .register-form {
        display: grid;
        gap: 2rem;
      }
      
      .form-group {
        position: relative;
      }
      
      .form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        font-family: 'Fredoka', 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.5;
        color: #1a202c;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.2s ease;
        box-sizing: border-box;
        font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
      }
      
      .form-input:focus {
        outline: none;
        border-color: #a8edea;
        box-shadow: 0 0 0 4px rgba(168, 237, 234, 0.2);
        transform: translateY(-2px);
      }
      
      .form-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
      }
      
      /* Input Icons */
      .form-group-with-icon {
        position: relative;
        margin-bottom: 0.5rem;
      }
      
      .form-group-with-icon::before {
        content: attr(data-icon);
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.125rem;
        color: #94a3b8;
        z-index: 1;
        pointer-events: none;
      }
      
      .form-group-with-icon .form-input {
        padding-left: 3rem;
      }
      
      /* Submit Button */
      .register-btn {
        width: 100%;
        padding: 1rem 1.5rem;
        font-family: 'Fredoka', 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border: none;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(168, 237, 234, 0.4);
        position: relative;
        overflow: hidden;
        margin-top: 1rem;
      }
      
      .register-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
      }
      
      .register-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(168, 237, 234, 0.5);
      }
      
      .register-btn:hover::before {
        left: 100%;
      }
      
      .register-btn:active {
        transform: translateY(0);
      }
      
      /* Footer Link */
      .register-footer {
        text-align: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
      }
      
      .login-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #38bdf8;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: rgba(56, 189, 248, 0.05);
        border: 1px solid rgba(56, 189, 248, 0.1);
      }
      
      .login-link:hover {
        background: rgba(56, 189, 248, 0.1);
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(56, 189, 248, 0.2);
      }
      
      /* Age Input Special Styling */
      .age-input-group {
        position: relative;
      }
      
      .age-helper {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: rgba(168, 237, 234, 0.1);
        border-radius: 8px;
        border: 1px solid rgba(168, 237, 234, 0.2);
      }
      
      /* Form Field Spacing - Fix để không dính sát */
      .form-fields-container {
        display: grid;
        gap: 1.75rem;
        margin-bottom: 1.5rem;
      }
      
      .form-field-wrapper {
        display: grid;
        gap: 0.75rem;
      }
      
      /* Responsive Design */
      @media (max-width: 480px) {
        body {
          padding: 1rem;
        }
        
        .register-card {
          padding: 2rem 1.5rem;
          border-radius: 20px;
        }
        
        .register-title {
          font-size: 1.5rem;
        }
        
        .form-input,
        .register-btn {
          padding: 0.875rem 1rem;
          font-size: 0.9375rem;
        }
        
        .form-group-with-icon .form-input {
          padding-left: 2.75rem;
        }
        
        .form-fields-container {
          gap: 1.5rem;
        }
      }
      
      @media (max-width: 360px) {
        .register-card {
          padding: 1.5rem 1rem;
        }
        
        .form-fields-container {
          gap: 1.25rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="register-container">
      <div class="register-card">
        <!-- Header -->
        <div class="register-header">
          <div class="register-icon">
            ✨
          </div>
          <h1 class="register-title">Tạo tài khoản mới</h1>
          <p class="register-subtitle">Bắt đầu hành trình học tập của bạn</p>
        </div>
        
        <!-- Alerts -->
        @if ($errors->any())
          <div class="register-alert error">
            <span>❌</span>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif
        
        <!-- Register Form -->
        <form method="POST" action="{{ route('register.submit') }}" class="register-form">
          @csrf
          <input type="hidden" name="book" value="{{ $book }}" />
          <input type="hidden" name="lesson" value="{{ $lesson }}" />
          
          <div class="form-fields-container">
            <div class="form-field-wrapper">
              <div class="form-group-with-icon" data-icon="👤">
                <input 
                  type="text" 
                  name="username" 
                  class="form-input"
                  placeholder="Tên đăng nhập" 
                  value="{{ old('username') }}"
                  required 
                  autocomplete="username"
                />
              </div>
            </div>
            
            <div class="form-field-wrapper">
              <div class="form-group-with-icon" data-icon="🔒">
                <input 
                  type="password" 
                  name="password" 
                  class="form-input"
                  placeholder="Mật khẩu" 
                  required 
                  autocomplete="new-password"
                />
              </div>
            </div>
            
            <div class="form-field-wrapper">
              <div class="age-input-group">
                <div class="form-group-with-icon" data-icon="🎂">
                  <input 
                    type="text" 
                    name="age" 
                    class="form-input"
                    placeholder="Tuổi (ví dụ: 8-10)" 
                    value="{{ old('age') }}"
                    required 
                  />
                </div>
                <div class="age-helper">
                  <span>💡</span>
                  <span>Nhập độ tuổi để chúng tôi có thể cá nhân hóa nội dung phù hợp</span>
                </div>
              </div>
            </div>
          </div>
          
          <button type="submit" class="register-btn">
            🚀 Tạo tài khoản
          </button>
        </form>
        
        <!-- Footer -->
        <div class="register-footer">
          <a href="{{ route('login', ['book' => $book, 'lesson' => $lesson]) }}" class="login-link">
            <span>🔐</span>
            <span>Đã có tài khoản? Đăng nhập ngay</span>
          </a>
        </div>
      </div>
    </div>
  </body>
</html>