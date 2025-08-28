<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>🔐 Đăng Nhập - VietFuture</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />

    <style>
        /* Login Page Specific Styles - Matching Register Design */
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #00a9ff 0%, #a0e9ff 50%, #cdf5fd 100%);
            font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Main Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 32px;
            padding: 2.5rem 2rem;
            box-shadow:
                0 20px 40px -8px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            animation: slideUp 0.8s cubic-bezier(0.23, 1, 0.32, 1);
            width: 100%;
            margin: 2rem 0;
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
            transform: translateY(-3px);
            box-shadow:
                0 25px 50px -8px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        /* Header Section */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00a9ff 0%, #89cff3 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 2rem;
            box-shadow:
                0 12px 28px rgba(0, 169, 255, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            animation: iconFloat 3s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        .login-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: iconShimmer 3s ease-in-out infinite;
        }

        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
                box-shadow: 0 12px 28px rgba(0, 169, 255, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }

            50% {
                transform: translateY(-4px) rotate(1deg);
                box-shadow: 0 16px 32px rgba(0, 169, 255, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }
        }

        @keyframes iconShimmer {
            0% {
                left: -100%;
            }

            50% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 0.75rem;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #1a202c 0%, #4a5568 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            margin: 0;
            font-weight: 500;
            line-height: 1.5;
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 1.125rem 1.5rem;
            font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.5;
            color: #1a202c;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            box-sizing: border-box;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
            backdrop-filter: blur(10px);
            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .form-input:focus {
            outline: none;
            border-color: #00a9ff;
            background: rgba(255, 255, 255, 1);
            box-shadow:
                0 0 0 4px rgba(0, 169, 255, 0.12),
                0 4px 16px rgba(0, 169, 255, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transform: translateY(-1px);
        }

        .form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Simplified Input - Remove icons for better alignment */
        .form-group-with-icon {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .form-group-with-icon .form-input {
            padding-left: 1.5rem;
        }

        /* Balance Placeholder for Visual Consistency with Register */
        .balance-placeholder {
            padding: 1.125rem 1.5rem;
            border: 2px dashed rgba(0, 169, 255, 0.3);
            border-radius: 20px;
            text-align: center;
            color: #00a9ff;
            font-size: 0.95rem;
            background: linear-gradient(135deg, rgba(0, 169, 255, 0.05) 0%, rgba(137, 207, 243, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            min-height: 64px;
            font-weight: 500;
            animation: gentle-pulse 4s ease-in-out infinite;
            backdrop-filter: blur(10px);
            box-shadow:
                0 2px 8px rgba(0, 169, 255, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        @keyframes gentle-pulse {

            0%,
            100% {
                background: linear-gradient(135deg, rgba(0, 169, 255, 0.05) 0%, rgba(137, 207, 243, 0.05) 100%);
                border-color: rgba(0, 169, 255, 0.3);
                box-shadow: 0 2px 8px rgba(0, 169, 255, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            }

            50% {
                background: linear-gradient(135deg, rgba(0, 169, 255, 0.08) 0%, rgba(137, 207, 243, 0.08) 100%);
                border-color: rgba(0, 169, 255, 0.4);
                box-shadow: 0 4px 12px rgba(0, 169, 255, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.4);
            }
        }

        /* Submit Button */
        .login-btn {
            width: 100%;
            padding: 1.25rem 2rem;
            font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #00a9ff 0%, #89cff3 100%);
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow:
                0 12px 28px rgba(0, 169, 255, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            margin-top: 1.5rem;
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
            transform: translateY(-3px);
            box-shadow:
                0 16px 32px rgba(0, 169, 255, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        /* Footer Link */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(226, 232, 240, 0.6);
        }

        .register-link {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            color: #00a9ff;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            padding: 1rem 1.75rem;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(0, 169, 255, 0.05) 0%, rgba(137, 207, 243, 0.05) 100%);
            border: 1px solid rgba(0, 169, 255, 0.15);
            backdrop-filter: blur(10px);
            box-shadow:
                0 2px 8px rgba(102, 126, 234, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .register-link:hover {
            background: linear-gradient(135deg, rgba(0, 169, 255, 0.08) 0%, rgba(137, 207, 243, 0.08) 100%);
            transform: translateY(-2px);
            box-shadow:
                0 8px 20px rgba(0, 169, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            border-color: rgba(0, 169, 255, 0.25);
        }

        /* Form Field Spacing - Optimized */
        .form-fields-container {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .form-field-wrapper {
            display: grid;
            gap: 0.5rem;
            position: relative;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .login-container {
                max-width: 100%;
                padding: 0 1rem;
            }

            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 28px;
                margin: 1rem 0;
            }

            .login-icon {
                width: 72px;
                height: 72px;
                font-size: 1.875rem;
            }

            .login-title {
                font-size: 1.75rem;
            }

            .login-subtitle {
                font-size: 1rem;
            }

            .form-input,
            .login-btn {
                padding: 1rem 1.25rem;
                font-size: 1rem;
                border-radius: 18px;
            }

            .form-group-with-icon .form-input {
                padding-left: 1.25rem;
            }

            .form-fields-container {
                gap: 1.25rem;
            }

            .register-link {
                padding: 0.875rem 1.5rem;
                font-size: 0.9375rem;
            }
        }

        @media (max-width: 360px) {
            .login-card {
                padding: 1.75rem 1.25rem;
                border-radius: 24px;
            }

            .login-icon {
                width: 64px;
                height: 64px;
                font-size: 1.75rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .form-fields-container {
                gap: 1rem;
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
                        <div class="form-group-with-icon">
                            <input type="text" name="username" class="form-input" placeholder="Tên đăng nhập"
                                value="{{ old('username') }}" required autocomplete="username" />
                        </div>
                    </div>

                    <div class="form-field-wrapper">
                        <div class="form-group-with-icon">
                            <input type="password" name="password" class="form-input" placeholder="Mật khẩu" required
                                autocomplete="current-password" />
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
