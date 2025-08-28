<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>🎮 Trò Chơi Học Tập - VietFuture</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Comic+Neue:wght@300;400;700&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Enhanced Kid-Friendly Styles for Quiz */
        body {
            font-family: 'Fredoka', 'Comic Neue', 'Inter', cursive;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Shapes */
        .quiz-bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
        }

        .quiz-shape {
            position: absolute;
            font-size: 2rem;
            animation: floatQuiz 8s ease-in-out infinite;
        }

        .quiz-shape:nth-child(1) {
            top: 15%;
            left: 5%;
            animation-delay: 0s;
            color: #ff6b6b;
        }

        .quiz-shape:nth-child(2) {
            top: 25%;
            right: 10%;
            animation-delay: 2s;
            color: #4ecdc4;
        }

        .quiz-shape:nth-child(3) {
            bottom: 25%;
            left: 10%;
            animation-delay: 4s;
            color: #45b7d1;
        }

        .quiz-shape:nth-child(4) {
            bottom: 15%;
            right: 15%;
            animation-delay: 6s;
            color: #f9ca24;
        }

        @keyframes floatQuiz {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            25% {
                transform: translateY(-15px) rotate(5deg);
            }

            50% {
                transform: translateY(-10px) rotate(-5deg);
            }

            75% {
                transform: translateY(-20px) rotate(3deg);
            }
        }

        /* Enhanced Header */
        .header-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 3px solid #ff6b6b;
            box-shadow: 0 8px 32px rgba(255, 107, 107, 0.3);
            position: relative;
            z-index: 10;
        }

        .logo-text {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: rainbow 3s ease-in-out infinite;
            font-weight: 700;
            font-size: 1.5rem;
        }

        @keyframes rainbow {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .nav-link {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .nav-emoji {
            font-size: 1.25rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            animation: gentleBounce 3s ease-in-out infinite;
        }

        @keyframes gentleBounce {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-2px) scale(1.05);
            }
        }

        /* Quiz Content Area */
        .wrap {
            position: relative;
            z-index: 5;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            margin: 2rem auto;
            max-width: 900px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 3px solid rgba(255, 255, 255, 0.2);
            min-height: 70vh;
        }

        #view {
            padding: 2rem;
            font-family: 'Fredoka', 'Comic Neue', cursive;
        }

        /* Quiz Cards */
        .card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 3px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24);
            background-size: 300% 100%;
            animation: rainbow 3s ease-in-out infinite;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: #ff6b6b;
        }

        /* Quiz Question Styling */
        .card h2,
        .card h3 {
            color: #2d3436;
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .card h2 {
            font-size: 1.75rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Buttons */
        .btn {
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            border-radius: 20px;
            padding: 0.875rem 1.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 1rem;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 184, 148, 0.4);
        }

        .btn-ghost {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-ghost:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Quiz Options */
        .quiz-options {
            display: grid;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .quiz-option {
            background: rgba(255, 255, 255, 0.9);
            border: 3px solid #e2e8f0;
            border-radius: 15px;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Fredoka', cursive;
            font-weight: 500;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quiz-option:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .quiz-option.selected {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border-color: #00b894;
        }

        .option-letter {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Progress Bar */
        .quiz-progress {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1);
            border-radius: 10px;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .progress-text {
            text-align: center;
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            color: #2d3436;
            font-size: 0.9rem;
        }

        /* Score Display */
        .score-display {
            background: linear-gradient(135deg, #f9ca24, #f0932b);
            color: white;
            padding: 1rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 1rem;
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            box-shadow: 0 5px 20px rgba(249, 202, 36, 0.3);
        }

        .score-number {
            font-size: 2rem;
            font-weight: 700;
            display: block;
            animation: pulse 2s infinite;
        }

        /* Confetti Canvas */
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1000;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .wrap {
                margin: 1rem;
                border-radius: 20px;
            }

            #view {
                padding: 1rem;
            }

            .card h2 {
                font-size: 1.5rem;
            }

            .quiz-option {
                padding: 0.875rem 1rem;
                font-size: 0.9rem;
            }

            .option-letter {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .quiz-shape {
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #667eea;
            animation: spin 1s ease-in-out infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Success Animations */
        .success-celebration {
            animation: celebration 0.6s ease-out;
        }

        @keyframes celebration {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1) rotate(5deg);
            }

            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        /* Error State */
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Mobile Navigation Improvements */
        @media (max-width: 768px) {
            .nav-link {
                padding: 10px 12px;
                gap: 6px;
                border-radius: 12px;
            }

            .nav-emoji {
                font-size: 1.125rem;
                width: 18px;
                height: 18px;
            }

            .nav-link span:not(.nav-emoji) {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .nav-link {
                padding: 8px 10px;
                gap: 4px;
                min-height: 44px;
                /* Touch target size */
            }

            .nav-emoji {
                font-size: 1rem;
                width: 16px;
                height: 16px;
            }

            .nav-link span:not(.nav-emoji) {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background Shapes for Quiz -->
    <div class="quiz-bg-shapes">
        <div class="quiz-shape">⭐</div>
        <div class="quiz-shape">🎯</div>
        <div class="quiz-shape">🏆</div>
        <div class="quiz-shape">💎</div>
    </div>

    <header class="header-nav fade-in">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M20 22V8a2 2 0 0 0-2-2h-7l-2-2H6a2 2 0 0 0-2 2v12" />
                    </svg>
                </div>
                <h1 class="logo-text">🎮 VietFuture Quiz</h1>
                <span class="user-info" id="kidTag"></span>
            </div>

            @if (session('user_id'))
                <nav class="nav-menu">
                    <a href="{{ route('quiz') }}" class="nav-link active">
                        <span class="nav-emoji">🎮</span>
                        <span>Quiz</span>
                    </a>
                    <a href="{{ route('parent') }}" class="nav-link">
                        <span class="nav-emoji">👨‍👩‍👧</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('community') }}" class="nav-link">
                        <span class="nav-emoji">💬</span>
                        <span>Cộng đồng</span>
                    </a>
                    <button onclick="showRewardShop()" class="nav-link">
                        <span class="nav-emoji">🛍️</span>
                        <span>Cửa hàng</span>
                    </button>
                    <button onclick="showCollection()" class="nav-link">
                        <span class="nav-emoji">📚</span>
                        <span>Bộ sưu tập</span>
                    </button>
                </nav>

                <div class="user-section">
                    <span class="user-name">
                        <span>👤</span>
                        {{ session('username', 'User') }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <span>🚪</span>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            @else
                <nav class="nav-menu">
                    <a href="{{ route('login', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}"
                        class="nav-link">
                        <span class="nav-emoji">🏠</span>
                        <span>Trang chủ</span>
                    </a>
                    <a href="{{ route('login', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}"
                        class="nav-link">
                        <span class="nav-emoji">🔐</span>
                        <span>Đăng nhập</span>
                    </a>
                    <a href="{{ route('register', ['book' => 'phong-chong-duoi-nuoc', 'lesson' => 'an-toan-nuoc']) }}"
                        class="nav-link">
                        <span class="nav-emoji">✨</span>
                        <span>Đăng ký</span>
                    </a>
                </nav>
            @endif
        </div>
    </header>

    <main class="wrap">
        <div id="view" style="padding: 2rem;">
            <!-- Quiz content will be loaded here by JavaScript -->
            <div class="card" style="text-align: center;">
                <div class="loading-spinner" style="margin: 2rem auto;"></div>
                <h2 style="font-family: 'Fredoka', cursive; color: #667eea; margin: 1rem 0;">
                    🎮 Đang tải trò chơi học tập thú vị...
                </h2>
                <p style="font-family: 'Fredoka', cursive; color: #94a3b8; font-size: 1rem;">
                    ⏳ Vui lòng chờ một chút nhé!
                </p>
            </div>
        </div>
    </main>

    <canvas class="confetti" id="confetti"></canvas>

    <script>
        window.Laravel = {
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        // Add some fun interactions for kids
        document.addEventListener('DOMContentLoaded', function() {
            // Animate shapes randomly
            const shapes = document.querySelectorAll('.quiz-shape');
            shapes.forEach((shape, index) => {
                setInterval(() => {
                    const randomX = Math.random() * 20 - 10;
                    const randomY = Math.random() * 20 - 10;
                    shape.style.transform =
                        `translate(${randomX}px, ${randomY}px) rotate(${Math.random() * 360}deg)`;
                }, 3000 + index * 1000);
            });

            // Add click effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });

        console.log('🌟 Welcome to VietFuture Quiz - Learning is fun! 🌟');
    </script>
    <script src="{{ asset('js/quiz.js') }}"></script>
</body>

</html>
