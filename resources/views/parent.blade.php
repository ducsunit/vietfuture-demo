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
                    <a href="#" onclick="alert('Cửa hàng chỉ có trong trang Quiz')" class="nav-link disabled">
                        <span class="nav-emoji">🛍️</span>
                        <span>Cửa hàng</span>
                    </a>
                    <a href="#" onclick="alert('Bộ sưu tập chỉ có trong trang Quiz')" class="nav-link disabled">
                        <span class="nav-emoji">📚</span>
                        <span>Bộ sưu tập</span>
                    </a>
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
        @php
            $total = $records->count();
            $avgScore = $total ? number_format($records->avg('score'), 1) : 0;
            $lastTime = $records->first()['created_at'] ?? null;
            $totalKids = $records->pluck('kidId')->filter()->unique()->count();
            $totalLessons = $records->pluck('lesson')->unique()->count();
            $points = session('point', 0);
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
        </script>
    @endif
</body>

</html>
