<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>🔐 Đăng Nhập - VietFuture</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Comic+Neue:wght@300;400;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    
    <style>
      /* Login Page Specific Styles - Matching Register Design */
      body {
        margin: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
          radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
          radial-gradient(circle at 80% 20%, rgba(255, 118, 117, 0.3) 0%, transparent 50%),
          radial-gradient(circle at 40% 80%, rgba(162, 155, 254, 0.3) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
      }
      
      @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
      }
      
      /* Login Container */
      .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 450px;
        padding: 0;
      }
      
      /* Main Login Card */
      .login-card {
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
      
      .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 
          0 35px 60px -12px rgba(0, 0, 0, 0.3),
          0 0 0 1px rgba(255, 255, 255, 0.1);
      }
      
      /* Header Section */
      .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
      }
      
      .login-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.75rem;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        animation: pulse 2s ease-in-out infinite;
      }
      
      @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
      }
      
      .login-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 0.5rem;
        letter-spacing: -0.02em;
      }
      
      .login-subtitle {
        font-size: 1rem;
        color: #64748b;
        margin: 0;
        font-weight: 400;
      }
      
      /* Alert Messages */
      .login-alert {
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
      
      .login-alert.success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #6ee7b7;
      }
      
      .login-alert.error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #f87171;
      }
      
      /* Form Styling */
      .login-form {
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
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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
      
      /* Balance Placeholder for Visual Consistency with Register */
      .balance-placeholder {
        padding: 1rem 1.25rem;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        text-align: center;
        color: #667eea;
        font-size: 0.875rem;
        background: rgba(102, 126, 234, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 60px;
        font-weight: 500;
        animation: gentle-pulse 3s ease-in-out infinite;
      }
      
      @keyframes gentle-pulse {
        0%, 100% { 
          background: rgba(102, 126, 234, 0.05);
          border-color: #e2e8f0;
        }
        50% { 
          background: rgba(102, 126, 234, 0.1);
          border-color: #667eea;
        }
      }
      
      /* Submit Button */
      .login-btn {
        width: 100%;
        padding: 1rem 1.5rem;
        font-family: 'Fredoka', 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
        margin-top: 1rem;
      }
      
      .login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
      }
      
      .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
      }
      
      .login-btn:hover::before {
        left: 100%;
      }
      
      .login-btn:active {
        transform: translateY(0);
      }
      
      /* Footer Link */
      .login-footer {
        text-align: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
      }
      
      .register-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: rgba(102, 126, 234, 0.05);
        border: 1px solid rgba(102, 126, 234, 0.1);
      }
      
      .register-link:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
      }
      
      /* Form Field Spacing - Same as Register */
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
        
        .login-card {
          padding: 2rem 1.5rem;
          border-radius: 20px;
        }
        
        .login-title {
          font-size: 1.5rem;
        }
        
        .form-input,
        .login-btn {
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
        .login-card {
          padding: 1.5rem 1rem;
        }
        
        .form-fields-container {
          gap: 1.25rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="login-card">
        <!-- Header -->
        <div class="login-header">
          <div class="login-icon">
            🔐
          </div>
          <h1 class="login-title">Chào mừng trở lại</h1>
          <p class="login-subtitle">Đăng nhập để tiếp tục học tập</p>
        </div>
        
        <!-- Alerts -->
        @if (session('success'))
          <div class="login-alert success">
            <span>✅</span>
            <span>{{ session('success') }}</span>
          </div>
        @endif
        
        @if ($errors->any())
          <div class="login-alert error">
            <span>❌</span>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('login.submit') }}" class="login-form">
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
                  autocomplete="current-password"
                />
              </div>
            </div>
            
            <!-- Balance placeholder to match register page layout -->
            <div class="form-field-wrapper">
              <div class="balance-placeholder">
                <span>🎯</span>
                <span>Sẵn sàng học tập? Hãy đăng nhập nào!</span>
              </div>
            </div>
          </div>
          
          <button type="submit" class="login-btn">
            🚀 Đăng nhập
          </button>
        </form>
        
        <!-- Footer -->
        <div class="login-footer">
          <a href="{{ route('register', ['book' => $book, 'lesson' => $lesson]) }}" class="register-link">
            <span>✨</span>
            <span>Chưa có tài khoản? Đăng ký ngay</span>
          </a>
        </div>
      </div>
    </div>
  </body>
</html>