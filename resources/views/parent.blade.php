<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>📊 Bảng Theo Dõi Học Tập - VietFuture</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/child-friendly-colors.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #00a9ff 0%, #a0e9ff 50%, #cdf5fd 100%);
        }

        .dash-wrap {
            padding: 24px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .stat {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 3px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat .icon {
            font-size: 28px;
        }

        .stat .value {
            font-size: 22px;
            font-weight: 800;
            color: #1f2937;
            line-height: 1;
        }

        .stat .label {
            color: #64748b;
            font-size: 13px;
            margin-top: 2px;
        }

        .filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 8px 0 20px;
        }

        .chip {
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 999px;
            background: #fff;
            color: #475569;
            cursor: pointer;
        }

        .chip.active {
            border-color: #00a9ff;
            color: #0369a1;
            background: #e6f7ff;
        }

        .record-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px;
            background: #fff;
            display: grid;
            gap: 6px;
        }

        .record-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e6f7ff;
            color: #0369a1;
            font-size: 12px;
            border: 1px solid #bae6fd;
        }

        .muted-sm {
            color: #94a3b8;
            font-size: 12px;
        }

        .grid-auto {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
        }

        /* Leaderboard Styles */
        .leaderboard-section {
            margin-bottom: 24px;
        }

        .leaderboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .leaderboard-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .leaderboard-refresh {
            background: linear-gradient(135deg, #00a9ff 0%, #0369a1 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .leaderboard-refresh:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 169, 255, 0.3);
        }

        .leaderboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .leaderboard-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 3px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .leaderboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
        }

        .leaderboard-card.silver::before {
            background: linear-gradient(90deg, #c0c0c0 0%, #e5e5e5 50%, #c0c0c0 100%);
        }

        .leaderboard-card.bronze::before {
            background: linear-gradient(90deg, #cd7f32 0%, #daa520 50%, #cd7f32 100%);
        }

        .rank-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
        }

        .rank-badge.gold {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
        }

        .rank-badge.silver {
            background: linear-gradient(135deg, #c0c0c0 0%, #e5e5e5 100%);
            box-shadow: 0 2px 8px rgba(192, 192, 192, 0.4);
        }

        .rank-badge.bronze {
            background: linear-gradient(135deg, #cd7f32 0%, #daa520 100%);
            box-shadow: 0 2px 8px rgba(205, 127, 50, 0.4);
        }

        .rank-badge.other {
            background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
            box-shadow: 0 2px 8px rgba(100, 116, 139, 0.4);
        }

        .leaderboard-user {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a9ff 0%, #0369a1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            font-weight: 600;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .user-stats {
            display: flex;
            gap: 16px;
            font-size: 14px;
            color: #64748b;
        }

        .user-points {
            background: linear-gradient(135deg, #00a9ff 0%, #0369a1 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin-top: 8px;
        }

        .leaderboard-empty {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
            font-style: italic;
        }

        .leaderboard-loading {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #00a9ff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Stats Overview Styles */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .overview-item {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }

        .overview-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .overview-icon {
            font-size: 24px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a9ff 0%, #0369a1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .overview-content {
            flex: 1;
        }

        .overview-value {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1;
        }

        .overview-label {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }

        /* Popular Lessons Styles */
        .popular-lessons {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .lesson-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .lesson-item:hover {
            border-color: #00a9ff;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%);
        }

        .lesson-name {
            font-weight: 500;
            color: #374151;
        }

        .lesson-count {
            background: linear-gradient(135deg, #00a9ff 0%, #0369a1 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Progress Summary Styles */
        .progress-summary {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .progress-chart {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
        }

        .chart-header {
            margin-bottom: 20px;
        }

        .score-distribution {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .score-bar {
            display: grid;
            grid-template-columns: 120px 1fr 80px;
            align-items: center;
            gap: 12px;
        }

        .score-label {
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .score-bar-container {
            height: 12px;
            background: #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.3s ease;
        }

        .score-bar-fill.excellent {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .score-bar-fill.good {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .score-bar-fill.needs-improvement {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .score-count {
            font-size: 12px;
            color: #64748b;
            text-align: right;
            font-weight: 500;
        }

        .progress-stats {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .progress-stat-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }

        .progress-stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .progress-stat-icon {
            font-size: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a9ff 0%, #0369a1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .progress-stat-content {
            flex: 1;
        }

        .progress-stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1;
        }

        .progress-stat-label {
            color: #64748b;
            font-size: 12px;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .leaderboard-grid {
                grid-template-columns: 1fr;
            }
            
            .leaderboard-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .stats-overview {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 12px;
            }

            .overview-item {
                padding: 12px;
            }

            .overview-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .overview-value {
                font-size: 18px;
            }

            .progress-summary {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .score-bar {
                grid-template-columns: 100px 1fr 60px;
                gap: 8px;
            }

            .score-label {
                font-size: 12px;
            }

            .score-count {
                font-size: 11px;
            }

            .progress-stat-item {
                padding: 12px;
            }

            .progress-stat-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }

            .progress-stat-value {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <header class="header-nav fade-in">
        <div class="header-container">
            <div class="logo-section">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M20 22V8a2 2 0 0 0-2-2h-7l-2-2H6a2 2 0 0 0-2 2v12" />
                    </svg>
                </div>
                <h1 class="logo-text">📊 VietFuture Dashboard</h1>
            </div>

            @if (session('user_id'))
                <nav class="nav-menu">
                    <a href="{{ route('quiz') }}" class="nav-link">
                        <span class="nav-emoji">🎮</span>
                        <span>Quiz</span>
                    </a>
                    <a href="{{ route('parent') }}" class="nav-link active">
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
            @endif
        </div>
    </header>
    <main class="wrap dash-wrap">
        <div id="view" style="display: none;"></div>
        <div id="dashboard-content">
        @php
            $total = $records->count();
            $avgScore = $total ? number_format($records->avg('score'), 1) : 0;
            $lastTime = $records->first()['created_at'] ?? null;
            $totalKids = $records->pluck('kidId')->filter()->unique()->count();
            $totalLessons = $records->pluck('lesson')->unique()->count();
            $points = session('point', 0);
            
            // Sử dụng thống kê từ controller
            $stats = $stats ?? [];
        @endphp

        <div class="stats">
            <div class="stat">
                <div class="icon">📚</div>
                <div>
                    <div class="value">{{ $totalLessons }}</div>
                    <div class="label">Bài học đã tham gia</div>
                </div>
            </div>
            <div class="stat">
                <div class="icon">👦</div>
                <div>
                    <div class="value">{{ $totalKids }}</div>
                    <div class="label">Trẻ em theo dõi</div>
                </div>
            </div>
            <div class="stat">
                <div class="icon">🏆</div>
                <div>
                    <div class="value">{{ $avgScore }}</div>
                    <div class="label">Điểm trung bình</div>
                </div>
            </div>
            <div class="stat">
                <div class="icon">💎</div>
                <div>
                    <div class="value">{{ (int) $points }}</div>
                    <div class="label">Điểm thưởng hiện có</div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        @if(!empty($stats))
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="margin-bottom: 16px;">📈 Thống Kê Tổng Quan</h2>
            <div class="stats-overview">
                <div class="overview-item">
                    <div class="overview-icon">📊</div>
                    <div class="overview-content">
                        <div class="overview-value">{{ number_format($stats['total_records'] ?? 0) }}</div>
                        <div class="overview-label">Tổng bản ghi</div>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="overview-icon">👥</div>
                    <div class="overview-content">
                        <div class="overview-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
                        <div class="overview-label">Người dùng tích cực</div>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="overview-icon">⭐</div>
                    <div class="overview-content">
                        <div class="overview-value">{{ number_format($stats['top_score'] ?? 0) }}</div>
                        <div class="overview-label">Điểm cao nhất</div>
                    </div>
                </div>
                <div class="overview-item">
                    <div class="overview-icon">🔥</div>
                    <div class="overview-content">
                        <div class="overview-value">{{ number_format($stats['recent_activity'] ?? 0) }}</div>
                        <div class="overview-label">Hoạt động tuần này</div>
                    </div>
                </div>
            </div>
            
            @if(!empty($stats['popular_lessons']))
            <div style="margin-top: 20px;">
                <h3 style="margin-bottom: 12px; color: #374151; font-size: 16px;">📚 Bài học phổ biến</h3>
                <div class="popular-lessons">
                    @foreach($stats['popular_lessons'] as $lesson => $count)
                    <div class="lesson-item">
                        <span class="lesson-name">{{ $lesson }}</span>
                        <span class="lesson-count">{{ $count }} lần</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Bảng xếp hạng -->
        <div class="card leaderboard-section">
            <div class="leaderboard-header">
                <h2 class="leaderboard-title">🏆 Bảng Xếp Hạng</h2>
                <button class="leaderboard-refresh" onclick="loadLeaderboard()">
                    <span>🔄</span>
                    <span>Cập nhật</span>
                </button>
            </div>
            <div id="leaderboardContainer">
                <div class="leaderboard-loading">
                    <div class="spinner"></div>
                    <div style="margin-top: 8px;">Đang tải bảng xếp hạng...</div>
                </div>
            </div>
        </div>

        <!-- Tiến trình học tập -->
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="margin-bottom: 16px;">📈 Tiến Trình Học Tập</h2>
            <div class="progress-summary">
                <div class="progress-chart">
                    <div class="chart-header">
                        <h3 style="margin: 0; color: #374151; font-size: 16px;">📊 Phân bố điểm số</h3>
                    </div>
                    <div class="score-distribution">
                        @php
                            $excellent = $records->where('score', '>=', 80)->count();
                            $good = $records->where('score', '>=', 50)->where('score', '<', 80)->count();
                            $needsImprovement = $records->where('score', '<', 50)->count();
                            $total = $records->count();
                            
                            $excellentPercent = $total > 0 ? round(($excellent / $total) * 100) : 0;
                            $goodPercent = $total > 0 ? round(($good / $total) * 100) : 0;
                            $needsImprovementPercent = $total > 0 ? round(($needsImprovement / $total) * 100) : 0;
                        @endphp
                        
                        <div class="score-bar">
                            <div class="score-label">Xuất sắc (≥80)</div>
                            <div class="score-bar-container">
                                <div class="score-bar-fill excellent" style="width: {{ $excellentPercent }}%"></div>
                            </div>
                            <div class="score-count">{{ $excellent }} ({{ $excellentPercent }}%)</div>
                        </div>
                        
                        <div class="score-bar">
                            <div class="score-label">Tốt (50-79)</div>
                            <div class="score-bar-container">
                                <div class="score-bar-fill good" style="width: {{ $goodPercent }}%"></div>
                            </div>
                            <div class="score-count">{{ $good }} ({{ $goodPercent }}%)</div>
                        </div>
                        
                        <div class="score-bar">
                            <div class="score-label">Cần cải thiện (<50)</div>
                            <div class="score-bar-container">
                                <div class="score-bar-fill needs-improvement" style="width: {{ $needsImprovementPercent }}%"></div>
                            </div>
                            <div class="score-count">{{ $needsImprovement }} ({{ $needsImprovementPercent }}%)</div>
                        </div>
                    </div>
                </div>
                
                <div class="progress-stats">
                    <div class="progress-stat-item">
                        <div class="progress-stat-icon">🎯</div>
                        <div class="progress-stat-content">
                            <div class="progress-stat-value">{{ $excellentPercent }}%</div>
                            <div class="progress-stat-label">Tỷ lệ xuất sắc</div>
                        </div>
                    </div>
                    
                    <div class="progress-stat-item">
                        <div class="progress-stat-icon">📈</div>
                        <div class="progress-stat-content">
                            <div class="progress-stat-value">{{ $avgScore }}</div>
                            <div class="progress-stat-label">Điểm trung bình</div>
                        </div>
                    </div>
                    
                    <div class="progress-stat-item">
                        <div class="progress-stat-icon">🚀</div>
                        <div class="progress-stat-content">
                            <div class="progress-stat-value">{{ $totalLessons }}</div>
                            <div class="progress-stat-label">Bài học hoàn thành</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 style="margin-bottom: 8px;">📊 Tiến trình gần đây</h2>
            <div class="muted" style="margin-bottom: 8px;">
                @if ($lastTime)
                    Cập nhật gần nhất: {{ $lastTime }}
                @endif
            </div>

            <div class="filters">
                <span class="chip active" onclick="filterCards('all')">Tất cả</span>
                <span class="chip" onclick="filterCards('>=80')">Điểm ≥ 80</span>
                <span class="chip" onclick="filterCards('50-79')">Điểm 50–79</span>
                <span class="chip" onclick="filterCards('<50')">Điểm < 50</span>
            </div>

            <div class="grid-auto" id="recordsGrid">
                @forelse($records as $r)
                    @php
                        $bucket = (int) $r['score'] >= 80 ? 'gte80' : ((int) $r['score'] >= 50 ? 'b50_79' : 'lt50');
                    @endphp
                    <div class="record-card" data-bucket="{{ $bucket }}">
                        <div class="record-head">
                            <div style="font-weight:700; color:#111827;">
                                👤 {{ $r['name'] ?? '—' }}
                                @if (!empty($r['kidId']))
                                    <span class="pill" title="Mã trẻ">🆔 {{ $r['kidId'] }}</span>
                                @endif
                            </div>
                            <div class="pill" title="Điểm">🏅 {{ $r['score'] }}</div>
                        </div>
                        <div>📘 <b>Bài:</b> {{ $r['lesson'] }}</div>
                        <div>🎂 <b>Tuổi:</b> {{ $r['age'] ?? '—' }}</div>
                        <div class="muted-sm">🕒 {{ $r['created_at'] }}</div>
                    </div>
                @empty
                    <div class="notice">Chưa có bản ghi nào.</div>
                @endforelse
            </div>
        </div>
        </div>
    </main>
    @if (session('user_id'))
        <script>
            function filterCards(rule) {
                const chips = document.querySelectorAll('.chip');
                chips.forEach(c => c.classList.remove('active'));
                const map = {
                    'all': 0,
                    '>=80': 1,
                    '50-79': 2,
                    '<50': 3
                };
                chips[map[rule]]?.classList.add('active');

                document.querySelectorAll('#recordsGrid .record-card').forEach(card => {
                    const bucket = card.getAttribute('data-bucket');
                    let show = true;
                    if (rule === '>=80') show = bucket === 'gte80';
                    if (rule === '50-79') show = bucket === 'b50_79';
                    if (rule === '<50') show = bucket === 'lt50';
                    card.style.display = show ? '' : 'none';
                });
            }

            // Load leaderboard function
            async function loadLeaderboard() {
                const container = document.getElementById('leaderboardContainer');
                container.innerHTML = `
                    <div class="leaderboard-loading">
                        <div class="spinner"></div>
                        <div style="margin-top: 8px;">Đang tải bảng xếp hạng...</div>
                    </div>
                `;

                try {
                    const response = await fetch('/api/leaderboard');
                    const data = await response.json();
                    
                    if (data.success) {
                        renderLeaderboard(data.leaderboard);
                    } else {
                        container.innerHTML = `
                            <div class="leaderboard-empty">
                                <div>😔 Không thể tải bảng xếp hạng</div>
                                <div style="margin-top: 8px; font-size: 14px;">${data.message || 'Vui lòng thử lại sau'}</div>
                            </div>
                        `;
                    }
                } catch (error) {
                    container.innerHTML = `
                        <div class="leaderboard-empty">
                            <div>😔 Lỗi kết nối</div>
                            <div style="margin-top: 8px; font-size: 14px;">Vui lòng kiểm tra kết nối mạng</div>
                        </div>
                    `;
                }
            }

            function renderLeaderboard(leaderboard) {
                const container = document.getElementById('leaderboardContainer');
                
                if (!leaderboard || leaderboard.length === 0) {
                    container.innerHTML = `
                        <div class="leaderboard-empty">
                            <div>📊 Chưa có dữ liệu xếp hạng</div>
                            <div style="margin-top: 8px; font-size: 14px;">Hãy tham gia quiz để có điểm số!</div>
                        </div>
                    `;
                    return;
                }

                const leaderboardHTML = leaderboard.map((user, index) => {
                    const rank = index + 1;
                    let rankClass = 'other';
                    let cardClass = '';
                    
                    if (rank === 1) {
                        rankClass = 'gold';
                        cardClass = '';
                    } else if (rank === 2) {
                        rankClass = 'silver';
                        cardClass = 'silver';
                    } else if (rank === 3) {
                        rankClass = 'bronze';
                        cardClass = 'bronze';
                    }

                    const displayName = user.display_name || user.username || 'Người dùng';
                    const firstLetter = displayName.charAt(0).toUpperCase();
                    
                    return `
                        <div class="leaderboard-card ${cardClass}">
                            <div class="rank-badge ${rankClass}">${rank}</div>
                            <div class="leaderboard-user">
                                <div class="user-avatar">${firstLetter}</div>
                                <div class="user-info">
                                    <div class="user-name">${displayName}</div>
                                    <div class="user-stats">
                                        <span>📚 ${user.total_lessons || 0} bài học</span>
                                        <span>👦 ${user.total_kids || 0} trẻ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="user-points">${user.point || 0} điểm</div>
                        </div>
                    `;
                }).join('');

                container.innerHTML = `
                    <div class="leaderboard-grid">
                        ${leaderboardHTML}
                    </div>
                `;
            }

            // Load leaderboard on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadLeaderboard();
            });

            // Add back button functionality
            window.showDashboard = function() {
                document.getElementById('view').style.display = 'none';
                document.getElementById('dashboard-content').style.display = 'block';
            };

            // Override showRewardShop and showCollection functions after quiz.js loads
            setTimeout(() => {
                if (window.showRewardShop) {
                    const originalShowRewardShop = window.showRewardShop;
                    window.showRewardShop = async function() {
                        document.getElementById('dashboard-content').style.display = 'none';
                        document.getElementById('view').style.display = 'block';
                        await originalShowRewardShop();
                    };
                }

                if (window.showCollection) {
                    const originalShowCollection = window.showCollection;
                    window.showCollection = async function() {
                        document.getElementById('dashboard-content').style.display = 'none';
                        document.getElementById('view').style.display = 'block';
                        await originalShowCollection();
                    };
                }
            }, 100);
        </script>
        <script src="{{ asset('js/quiz.js') }}"></script>
    @endif
</body>

</html>
