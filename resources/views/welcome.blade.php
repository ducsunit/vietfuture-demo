<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VietFuture - Học Kỹ Năng Sống Vui Vẻ!</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Comic+Neue:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Fredoka', 'Comic Neue', cursive;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Animated Background Elements */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            font-size: 3rem;
            animation-delay: 0s;
            color: #ff6b6b;
        }
        
        .shape:nth-child(2) {
            top: 20%;
            right: 15%;
            font-size: 2.5rem;
            animation-delay: 1s;
            color: #4ecdc4;
        }
        
        .shape:nth-child(3) {
            bottom: 30%;
            left: 5%;
            font-size: 2rem;
            animation-delay: 2s;
            color: #45b7d1;
        }
        
        .shape:nth-child(4) {
            bottom: 20%;
            right: 20%;
            font-size: 3.5rem;
            animation-delay: 3s;
            color: #f9ca24;
        }
        
        .shape:nth-child(5) {
            top: 50%;
            left: 80%;
            font-size: 2.2rem;
            animation-delay: 4s;
            color: #6c5ce7;
        }
        
        .shape:nth-child(6) {
            top: 70%;
            left: 15%;
            font-size: 2.8rem;
            animation-delay: 5s;
            color: #a29bfe;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
        /* Header */
        .header {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 3px solid #ff6b6b;
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(255, 107, 107, 0.2);
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff6b6b;
            text-decoration: none;
        }
        
        .logo-icon {
            font-size: 2rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #ff6b6b;
            border: 2px solid #ff6b6b;
        }
        
        .btn-secondary:hover {
            background: #ff6b6b;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            z-index: 5;
            text-align: center;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            animation: slideInDown 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            color: #636e72;
            margin-bottom: 3rem;
            font-weight: 400;
            animation: slideInUp 1s ease-out 0.3s both;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
            animation: fadeIn 1s ease-out 0.6s both;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 3px solid transparent;
            cursor: pointer;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 45px rgba(0,0,0,0.15);
            border-color: #ff6b6b;
        }
        
        .feature-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 1rem;
        }
        
        .feature-description {
            color: #636e72;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 3rem 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            margin: 3rem auto;
            max-width: 800px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: slideInUp 1s ease-out 0.9s both;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 1rem;
        }
        
        .cta-subtitle {
            font-size: 1.2rem;
            color: #636e72;
            margin-bottom: 2rem;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .cta-btn {
            padding: 1rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .cta-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .cta-btn:hover::before {
            left: 100%;
        }
        
        .btn-start {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.3);
        }
        
        .btn-start:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 184, 148, 0.4);
        }
        
        .btn-demo {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 92, 231, 0.3);
        }
        
        .btn-demo:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(108, 92, 231, 0.4);
        }
        
        /* Footer */
        .footer {
            position: relative;
            z-index: 10;
            background: rgba(45, 52, 54, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-text {
            font-size: 1rem;
            opacity: 0.8;
        }
        
        /* Special Animations for Kids */
        .wobble {
            animation: wobble 2s ease-in-out infinite;
        }
        
        @keyframes wobble {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(3deg); }
            75% { transform: rotate(-3deg); }
        }
        
        .rainbow-text {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24, #6c5ce7, #ff6b6b);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbow 3s ease-in-out infinite;
        }
        
        @keyframes rainbow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .nav-links {
                gap: 0.5rem;
            }
            
            .nav-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
            
            .features {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-btn {
                width: 100%;
                max-width: 300px;
            }
            
            .shape {
                font-size: 1.5rem !important;
            }
        }
        
        @media (max-width: 480px) {
            .hero {
                padding: 2rem 1rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .header {
                padding: 0.75rem 1rem;
            }
            
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="shape">🌟</div>
        <div class="shape">🎈</div>
        <div class="shape">🌈</div>
        <div class="shape">🦄</div>
        <div class="shape">🎨</div>
        <div class="shape">🎵</div>
    </div>
    
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">
                <span class="logo-icon">🚀</span>
                <span class="rainbow-text">VietFuture</span>
            </a>
            
            <nav class="nav-links">
                @auth
                    <a href="{{ route('quiz', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="nav-btn btn-primary">
                        🎯 Vào Học
                    </a>
                @else
                    <a href="{{ route('login', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="nav-btn btn-secondary">
                        🔐 Đăng nhập
                    </a>
                    <a href="{{ route('register', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="nav-btn btn-primary">
                        ✨ Đăng ký
                    </a>
                @endauth
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <main class="hero">
        <h1 class="hero-title">
            Chào mừng đến với <br>
            <span class="rainbow-text">Thế Giới Kỹ Năng Sống!</span>
        </h1>
        <p class="hero-subtitle">
            🌟 Học cách bảo vệ bản thân một cách vui vẻ và thú vị 🌟
        </p>
        
        <!-- Features -->
        <div class="features">
            <div class="feature-card">
                <span class="feature-icon">🏊‍♀️</span>
                <h3 class="feature-title">An Toàn Dưới Nước</h3>
                <p class="feature-description">
                    Học cách bơi an toàn và biết phòng tránh đuối nước qua những trò chơi thú vị
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🧠</span>
                <h3 class="feature-title">Trò Chơi Thông Minh</h3>
                <p class="feature-description">
                    Câu hỏi tương tác và trò chơi giúp em nhớ lâu hơn những kỹ năng quan trọng
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🏆</span>
                <h3 class="feature-title">Nhận Phần Thưởng</h3>
                <p class="feature-description">
                    Tích điểm và nhận những phần thưởng đặc biệt khi hoàn thành các bài học
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">👨‍👩‍👧‍👦</span>
                <h3 class="feature-title">Cùng Gia Đình</h3>
                <p class="feature-description">
                    Chia sẻ kết quả học tập với bố mẹ và cùng nhau thảo luận về an toàn
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">🌍</span>
                <h3 class="feature-title">Cộng Đồng</h3>
                <p class="feature-description">
                    Kết nối với bạn bè và chia sẻ kinh nghiệm học tập cùng nhau
                </p>
            </div>
            
            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <h3 class="feature-title">Học Mọi Lúc</h3>
                <p class="feature-description">
                    Truy cập dễ dàng trên điện thoại, máy tính bảng để học mọi lúc mọi nơi
                </p>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="cta-section">
            <h2 class="cta-title wobble">🎉 Sẵn sàng bắt đầu chưa? 🎉</h2>
            <p class="cta-subtitle">
                Cùng nhau khám phá những kỹ năng sống thú vị và bổ ích!
            </p>
            
            <div class="cta-buttons">
                @auth
                    <a href="{{ route('quiz', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="cta-btn btn-start">
                        🚀 Bắt Đầu Học Ngay
                    </a>
                    <a href="{{ route('parent') }}" class="cta-btn btn-demo">
                        📊 Xem Tiến Độ
                    </a>
                @else
                    <a href="{{ route('register', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="cta-btn btn-start">
                        🌟 Tạo Tài Khoản Miễn Phí
                    </a>
                    <a href="{{ route('quiz', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}" class="cta-btn btn-demo">
                        🎮 Dùng Thử Ngay
                    </a>
                @endauth
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">
                🌟 VietFuture - Nơi trẻ em học kỹ năng sống một cách vui vẻ và an toàn 🌟
            </p>
            <p class="footer-text" style="margin-top: 0.5rem; font-size: 0.9rem;">
                © {{ date('Y') }} VietFuture. Được thiết kế với ❤️ cho các em nhỏ Việt Nam
            </p>
        </div>
    </footer>
    
    <script>
        // Add some interactive elements for kids
        document.addEventListener('DOMContentLoaded', function() {
            // Make feature cards interactive
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
            
            // Add click effect to CTA buttons
            const ctaButtons = document.querySelectorAll('.cta-btn');
            ctaButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const ripple = document.createElement('div');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.left = e.offsetX - 10 + 'px';
                    ripple.style.top = e.offsetY - 10 + 'px';
                    ripple.style.width = '20px';
                    ripple.style.height = '20px';
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Random shape movements
            const shapes = document.querySelectorAll('.shape');
            shapes.forEach((shape, index) => {
                setInterval(() => {
                    const randomX = Math.random() * 10 - 5;
                    const randomY = Math.random() * 10 - 5;
                    shape.style.transform = `translate(${randomX}px, ${randomY}px) rotate(${Math.random() * 360}deg)`;
                }, 2000 + index * 500);
            });
        });
        
        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        console.log('🌟 Welcome to VietFuture - Where learning life skills is fun! 🌟');
    </script>
</body>
</html>
