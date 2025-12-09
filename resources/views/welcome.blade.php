<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk System - PT. Mesin Isuzu Indonesia</title>
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('public/templates/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/templates/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/templates/dist/css/adminlte.min.css') }}">
    
    <style>
        body { 
            background: linear-gradient(180deg, #0c4a6e 0%, #164e63 50%, #155e75 100%); 
            min-height: 100vh; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Remove default AdminLTE margins */
        .wrapper {
            margin: 0 !important;
        }
        
        /* Navbar Custom AdminLTE */
        .navbar-custom {
            background: rgba(8, 47, 73, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 100;
            margin: 0 !important;
        }
        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand-custom {
            color: white !important;
            font-size: 1.2em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none !important;
        }
        .logo-icon { 
            width: 42px; 
                height: 42px; 
                background: white; 
            border-radius: 8px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 5px;
            overflow: hidden;
        }
        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .login-petugas-btn {
            background: #3b82f6 !important;
            border: none;
            color: white !important;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(59,130,246,0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .login-petugas-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59,130,246,0.4);
            background: #2563eb !important;
            color: white !important;
        }
        
        /* Hero Section */
        .hero-section {
            text-align: center;
            padding: 50px 20px 35px;
            color: white;
            background: transparent;
        }
        .hero-logo { 
            width: 170px; 
            height: 170px; 
            border-radius: 20px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 25px; 
            padding: 15px;
            animation: float 3s ease-in-out infinite;
            overflow: hidden;
        }
        .hero-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .hero-title {
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }
        .hero-subtitle {
            font-size: 1.15em;
            margin-bottom: 30px;
            opacity: 0.95;
            text-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-user, .btn-register {
            padding: 13px 35px;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            color: white !important;
        }
        .btn-user {
            background: #06b6d4 !important;
            box-shadow: 0 6px 16px rgba(6,182,212,0.3);
        }
        .btn-user:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6,182,212,0.4);
            background: #0891b2 !important;
        }
        .btn-register {
            background: #10b981 !important;
            box-shadow: 0 6px 16px rgba(16,185,129,0.3);
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16,185,129,0.4);
            background: #059669 !important;
        }
        
        /* Content Container */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px 40px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: rgba(255,255,255,0.98) !important;
            backdrop-filter: blur(10px);
            border-radius: 16px !important;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25) !important;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.35) !important;
        }
        .stat-number {
            font-size: 3em;
            font-weight: 800;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #0c4a6e, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .stat-label {
            font-size: 1em;
            color: #64748b;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        /* Table Section */
        .table-section {
            background: rgba(255,255,255,0.98) !important;
            backdrop-filter: blur(10px);
            border-radius: 16px !important;
            padding: 0;
            box-shadow: 0 10px 35px rgba(0,0,0,0.25) !important;
            overflow: hidden;
            border: none !important;
        }
        .table-header {
            background: linear-gradient(135deg, #0c4a6e, #155e75) !important;
            color: white !important;
            padding: 22px 28px !important;
            border: none !important;
        }
        .table-header h2 {
            color: white !important;
            font-size: 1.5em;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .table-wrapper { 
            overflow-x: auto;
        }
        .table {
            margin-bottom: 0;
            width: 100%;
        }
        .table thead th {
            background: #f8fafc !important;
            color: #0c4a6e !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8em;
            letter-spacing: 0.5px;
            padding: 16px 14px !important;
            border-bottom: 2px solid #e2e8f0 !important;
            white-space: nowrap;
            text-align: center;
        }
        .table thead th:first-child,
        .table thead th:nth-child(2) {
            text-align: left;
        }
        .table tbody td {
            padding: 16px 14px !important;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid #f1f5f9 !important;
            text-align: center;
        }
        .table tbody td:first-child,
        .table tbody td:nth-child(2) {
            text-align: left;
        }
        .table tbody td:nth-child(2) {
            font-weight: 600;
            color: #0f172a;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background: #f8fafc !important;
        }
        .badge {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 0.85em;
            font-weight: 700;
            min-width: 50px;
            text-align: center;
            display: inline-block;
        }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fef3c7; color: #b45309; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-purple { background: #f3e8ff; color: #7c3aed; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }
        .empty-state i {
            font-size: 4em;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        .empty-state p {
            color: #94a3b8;
            font-size: 1.1em;
        }
        
        /* Footer */
        .footer-custom {
            background: rgba(8,47,73,0.95);
            backdrop-filter: blur(10px);
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
            border-top: 3px solid rgba(6,182,212,0.3);
        }
        .footer-custom p {
            margin: 0;
            font-size: 0.95em;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .navbar-content {
                padding: 0 20px;
            }
            .main-content {
                padding: 0 20px 30px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid { 
                grid-template-columns: 1fr;
            }
            .hero-buttons { 
                flex-direction: column; 
                align-items: stretch;
                max-width: 300px;
                margin: 0 auto;
            }
            .btn-user, .btn-register { 
                width: 100%;
                justify-content: center; 
            }
            .hero-title {
                font-size: 1.6em;
            }
            .hero-subtitle {
                font-size: 1em;
            }
            .navbar-content {
                padding: 0 15px;
            }
            .navbar-brand-custom {
                font-size: 1em;
            }
            .table-header h2 {
                font-size: 1.2em;
            }
            .table thead th {
                font-size: 0.7em;
                padding: 12px 8px !important;
            }
            .table tbody td {
                padding: 12px 8px !important;
                font-size: 0.85em;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar-custom">
            <div class="navbar-content">
                <a href="{{ url('/') }}" class="navbar-brand-custom">
                    <div class="logo-icon">
                        <img src="{{ asset('public/templates/dist/img/Logo_HelpDesk.png') }}" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    <span>Helpdesk System</span>
                </a>
                <a href="{{ route('login_petugas') }}" class="login-petugas-btn">
                    <i class="fas fa-lock"></i> Login Petugas
                </a>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-logo">
                <img src="{{ asset('public/templates/dist/img/LogoIsuzu.png') }}" alt="Logo Isuzu" onerror="this.style.display='none'">
            </div>
            <h1 class="hero-title">Selamat Datang di Layanan Helpdesk</h1>
            <p class="hero-subtitle">Sistem Manajemen Tiket PT. Mesin Isuzu Indonesia</p>
            <div class="hero-buttons">
                <a href="{{ route('login_user') }}" class="btn-user">
                    <i class="fas fa-user"></i> Login User
                </a>
                <a href="{{ route('register_user') }}" class="btn-register">
                    <i class="fas fa-user-plus"></i> Daftar User
                </a>
            </div>
        </section>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Tiket::count() ?? 0 }}</div>
                    <div class="stat-label"><i class="fas fa-inbox"></i> Total Tiket</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Tiket::whereIn('status', ['pending', 'progress'])->count() ?? 0 }}</div>
                    <div class="stat-label"><i class="fas fa-spinner"></i> Dalam Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Tiket::where('status', 'finish')->count() ?? 0 }}</div>
                    <div class="stat-label"><i class="fas fa-check-circle"></i> Selesai</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Tiket::where('status', 'closed')->count() ?? 0 }}</div>
                    <div class="stat-label"><i class="fas fa-lock"></i> Closed</div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-section">
                <div class="table-header">
                    <h2><i class="fas fa-chart-bar"></i> Statistik Tiket Per Departemen</h2>
                </div>
                <div class="table-wrapper">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width:60px;">NO</th>
                                <th>DEPARTEMEN</th>
                                <th style="width:90px;">TOTAL</th>
                                <th style="width:90px;">OPEN</th>
                                <th style="width:100px;">PENDING</th>
                                <th style="width:100px;">PROGRESS</th>
                                <th style="width:90px;">FINISH</th>
                                <th style="width:90px;">CLOSED</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $stats = \App\Models\Departemen::withCount([
                                    'tiket',
                                    'tiket as open_count' => fn($q) => $q->where('status', 'open'),
                                    'tiket as pending_count' => fn($q) => $q->where('status', 'pending'),
                                    'tiket as progress_count' => fn($q) => $q->where('status', 'progress'),
                                    'tiket as finish_count' => fn($q) => $q->where('status', 'finish'),
                                    'tiket as closed_count' => fn($q) => $q->where('status', 'closed')
                                ])->orderBy('nama_departemen')->get();
                            @endphp
                            @forelse($stats as $index => $dept)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $dept->nama_departemen }}</td>
                                    <td><span class="badge badge-blue">{{ $dept->tiket_count }}</span></td>
                                    <td><span class="badge badge-gray">{{ $dept->open_count }}</span></td>
                                    <td><span class="badge badge-yellow">{{ $dept->pending_count }}</span></td>
                                    <td><span class="badge badge-blue">{{ $dept->progress_count }}</span></td>
                                    <td><span class="badge badge-green">{{ $dept->finish_count }}</span></td>
                                    <td><span class="badge badge-purple">{{ $dept->closed_count }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Belum ada data tiket</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer-custom">
            <p><strong>&copy; {{ date('Y') }} PT. Mesin Isuzu Indonesia</strong> - Helpdesk Ticketing System</p>
        </footer>
    </div>

    <!-- AdminLTE JS -->
    <script src="{{ asset('public/templates/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/templates/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
