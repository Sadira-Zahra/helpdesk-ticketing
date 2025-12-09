@php
use Illuminate\Support\Facades\Storage;
$user = auth()->user();
$initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
$avatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($user->nama ?? 'User') . '&background=1e3a8a&color=fff&bold=true&size=128';

// Cache busting untuk refresh foto
$avatarSrc = $avatarFallback;
if ($user->photo) {
    // Cek apakah file benar-benar ada di storage
    if (Storage::disk('public')->exists($user->photo)) {
        $avatarSrc = asset('storage/' . $user->photo) . '?v=' . time();
    }
}
@endphp

<style>
/* Clean White Navbar */
.main-header.navbar {
    background: #ffffff !important;
    border-bottom: 2px solid #e5e7eb;
    box-shadow: 0 1px 10px rgba(0, 0, 0, 0.08);
    height: 57px;
}

.navbar-nav .nav-link {
    color: #374151 !important;
    transition: all 0.3s ease;
    font-weight: 500;
    padding: 0.5rem 1rem !important;
}

.navbar-nav .nav-link:hover {
    color: #1e3a8a !important;
    background: #f3f4f6;
    border-radius: 8px;
}

.navbar-nav .nav-link i {
    color: #6b7280;
}

.navbar-nav .nav-link:hover i {
    color: #1e3a8a;
}

/* User Dropdown */
.user-menu .nav-link {
    display: flex;
    align-items: center;
    padding: 0.25rem 1rem !important;
}

.user-menu .nav-link img {
    border: 2px solid #e5e7eb;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.user-menu .nav-link:hover img {
    border-color: #1e3a8a;
    transform: scale(1.05);
}

.user-menu .nav-link span {
    color: #111827;
    font-weight: 600;
}

/* Dropdown Menu */
.dropdown-menu {
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 8px;
}

.dropdown-item {
    padding: 12px 20px;
    transition: all 0.3s ease;
    color: #374151;
}

.dropdown-item:hover {
    background: #f3f4f6;
    color: #1e3a8a;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
    margin-right: 10px;
}

.dropdown-divider {
    margin: 0;
    border-top: 1px solid #f3f4f6;
}

/* User Info Card */
.user-info-card {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: white;
    padding: 15px;
    border-radius: 0;
}

.user-info-card .user-name {
    font-weight: 700;
    font-size: 1.1em;
    margin: 0;
}

.user-info-card .user-role {
    font-size: 0.85em;
    opacity: 0.9;
    margin: 0;
}

/* Logout Button Style */
.dropdown-item.logout-btn {
    background: #dc3545;
    color: white;
    font-weight: 600;
    margin: 8px;
    border-radius: 8px;
}

.dropdown-item.logout-btn:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

/* Sidebar Toggle Button */
.nav-link[data-widget="pushmenu"] {
    color: #374151 !important;
}

.nav-link[data-widget="pushmenu"]:hover {
    background: #f3f4f6 !important;
    border-radius: 8px;
    color: #1e3a8a !important;
}

/* Fullscreen Button */
.nav-link[data-widget="fullscreen"] {
    color: #374151 !important;
}

.nav-link[data-widget="fullscreen"]:hover {
    background: #f3f4f6 !important;
    border-radius: 8px;
    color: #1e3a8a !important;
}

/* Avatar Styling */
.user-avatar {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 50%;
}

.user-avatar-large {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .user-menu .nav-link span {
        display: none !important;
    }
}
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="fas fa-home mr-1"></i> Home
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ $avatarSrc }}" 
                     class="img-circle elevation-1 user-avatar" 
                     alt="{{ $user->nama }}"
                     onerror="this.src='{{ $avatarFallback }}'">
                <span class="d-none d-md-inline ml-2">{{ $user->nama }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="userDropdown">
                <!-- User Info Card -->
                <div class="user-info-card">
                    <div class="d-flex align-items-center">
                        <img src="{{ $avatarSrc }}" 
                             class="img-circle elevation-2 user-avatar-large mr-3" 
                             alt="{{ $user->nama }}"
                             onerror="this.src='{{ $avatarFallback }}'">
                        <div>
                            <p class="user-name">{{ $user->nama }}</p>
                            <p class="user-role">
                                <i class="fas fa-user-shield mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <!-- Profile Link -->
                <a href="{{ route('ganti_profil.index') }}" class="dropdown-item">
                    <i class="fas fa-user-circle text-primary"></i>
                    Profil Saya
                </a>

                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
