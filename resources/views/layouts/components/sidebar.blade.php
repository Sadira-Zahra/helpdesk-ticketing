{{-- Custom Sidebar Styles - White Theme --}}
<style>
/* ========================================
   SIDEBAR - White Theme
   ======================================== */
.main-sidebar {
    background: #ffffff !important;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08) !important;
    border-right: 1px solid #e5e7eb !important;
    transition: all 0.3s ease !important;
}

/* ========================================
   BRAND LINK
   ======================================== */
.brand-link {
    background: #ffffff !important;
    border-bottom: 2px solid #e5e7eb !important;
    padding: 0.8125rem 1rem !important;
    height: 57px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s ease !important;
}

.brand-link .brand-image {
    border: 2px solid #e5e7eb !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    max-height: 38px !important;
    width: auto !important;
    transition: all 0.3s ease !important;
}

.brand-text {
    color: #1e3a8a !important;
    font-weight: 700 !important;
    font-size: 1.1rem !important;
    margin-left: 0.5rem !important;
    transition: all 0.3s ease !important;
}

/* Sidebar Collapsed - Brand */
.sidebar-collapse .brand-link {
    justify-content: center !important;
}

.sidebar-collapse .brand-text {
    display: none !important;
}

.sidebar-collapse .brand-image {
    margin: 0 auto !important;
}

/* ========================================
   SIDEBAR MENU
   ======================================== */
.sidebar {
    padding-top: 0 !important;
    overflow-x: hidden !important;
}

/* Nav Links */
.nav-sidebar .nav-link {
    color: #4b5563 !important;
    border-radius: 8px !important;
    margin: 4px 8px !important;
    padding: 0.7rem 1rem !important;
    transition: all 0.3s ease !important;
    border-left: 3px solid transparent !important;
    position: relative !important;
}

.nav-sidebar .nav-link:hover {
    background: #f3f4f6 !important;
    color: #1e3a8a !important;
    transform: translateX(3px) !important;
    border-left-color: #1e3a8a !important;
}

.nav-sidebar .nav-link.active {
    background: #eff6ff !important;
    color: #1e3a8a !important;
    font-weight: 600 !important;
    border-left-color: #1e3a8a !important;
    box-shadow: 0 2px 4px rgba(30, 58, 138, 0.1) !important;
}

.nav-sidebar .nav-link p {
    color: inherit !important;
    display: inline-block !important;
    margin: 0 !important;
}

/* Nav Icons */
.nav-sidebar .nav-icon {
    color: #6b7280 !important;
    margin-right: 10px !important;
    width: 20px !important;
    text-align: center !important;
    transition: all 0.3s ease !important;
}

.nav-sidebar .nav-link:hover .nav-icon,
.nav-sidebar .nav-link.active .nav-icon {
    color: #1e3a8a !important;
}

/* Arrow Icon for Treeview */
.nav-link .right {
    transition: transform 0.3s ease !important;
}

.menu-open > .nav-link .right {
    transform: rotate(-90deg) !important;
}

/* ========================================
   SUBMENU / TREEVIEW
   ======================================== */
.nav-treeview {
    background: transparent !important;
    padding-left: 0 !important;
}

.nav-treeview .nav-link {
    padding-left: 3rem !important;
    margin: 2px 8px !important;
    border-left: 3px solid transparent !important;
}

.nav-treeview .nav-link:hover {
    background: #f3f4f6 !important;
    border-left-color: #93c5fd !important;
}

.nav-treeview .nav-link.active {
    background: #dbeafe !important;
    border-left-color: #3b82f6 !important;
}

.nav-treeview .nav-icon {
    font-size: 0.85rem !important;
}

/* Parent Menu Open State */
.nav-item.menu-open > .nav-link {
    background: #eff6ff !important;
    color: #1e3a8a !important;
}

.nav-item.menu-open > .nav-link .nav-icon {
    color: #1e3a8a !important;
}

/* ========================================
   SIDEBAR COLLAPSED STATE
   ======================================== */
.sidebar-collapse .nav-sidebar .nav-link {
    width: calc(100% - 16px) !important;
    margin: 4px 8px !important;
}

.sidebar-collapse .nav-sidebar .nav-link p,
.sidebar-collapse .nav-sidebar .nav-link .right {
    display: none !important;
}

.sidebar-collapse .nav-sidebar .nav-icon {
    margin-right: 0 !important;
}

/* Sidebar Collapsed - Submenu Hidden */
.sidebar-collapse .nav-treeview {
    display: none !important;
}

/* Sidebar Collapsed - Center Icon */
.sidebar-collapse .nav-sidebar > .nav-item > .nav-link {
    text-align: center !important;
    padding: 0.7rem 0 !important;
}

.sidebar-collapse .nav-sidebar .nav-link {
    border-left: none !important;
}

/* Sidebar Collapsed - Hover Tooltip */
.sidebar-collapse .nav-sidebar .nav-link:hover::after {
    content: attr(data-title) !important;
    position: absolute !important;
    left: 100% !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: #1e3a8a !important;
    color: #ffffff !important;
    padding: 0.5rem 1rem !important;
    border-radius: 6px !important;
    white-space: nowrap !important;
    z-index: 9999 !important;
    margin-left: 10px !important;
    font-size: 0.875rem !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    pointer-events: none !important;
}

.sidebar-collapse .nav-sidebar .nav-link:hover::before {
    content: '' !important;
    position: absolute !important;
    left: 100% !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    border: 6px solid transparent !important;
    border-right-color: #1e3a8a !important;
    margin-left: 4px !important;
    z-index: 9999 !important;
}

/* ========================================
   LOGOUT BUTTON
   ======================================== */
.nav-link.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
    color: #ffffff !important;
    border-radius: 8px !important;
    margin: 4px 8px !important;
    font-weight: 600 !important;
    border: none !important;
}

.nav-link.bg-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3) !important;
}

.nav-link.bg-danger .nav-icon {
    color: #ffffff !important;
}

.sidebar-collapse .nav-link.bg-danger {
    text-align: center !important;
    padding: 0.7rem 0 !important;
}

/* ========================================
   SCROLLBAR
   ======================================== */
.sidebar::-webkit-scrollbar {
    width: 6px !important;
}

.sidebar::-webkit-scrollbar-track {
    background: #f9fafb !important;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #d1d5db !important;
    border-radius: 3px !important;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af !important;
}

/* ========================================
   OVERRIDE ADMINLTE DARK THEME
   ======================================== */
.sidebar-dark-primary {
    background: #ffffff !important;
}

.sidebar-dark-primary .nav-link {
    color: #4b5563 !important;
}

/* ========================================
   RESPONSIVE
   ======================================== */
@media (max-width: 991.98px) {
    .sidebar-collapse .main-sidebar {
        margin-left: -250px !important;
    }
}

/* Smooth Transitions */
* {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
}
</style>

@php
function item($title, $path = '', $icon = 'fas fa-circle', $extra = []) {
    return (object) array_merge(['title' => $title, 'path' => $path, 'icon' => $icon], $extra);
}

function linkFor($path) {
    if (empty($path)) return '#';
    if (strpos($path, '.') !== false) {
        try { return route($path); } catch (Exception $e) { return url($path); }
    }
    if (preg_match('/https?:\/\//', $path)) return $path;
    return url($path);
}

function isActive($path) {
    if (empty($path) || $path === '#') return false;
    $href = linkFor($path);
    $current = url(request()->path());
    return rtrim($href, '/') === rtrim($current, '/');
}

function isActiveParent($submenu) {
    foreach ($submenu as $item) {
        if (isActive($item->path)) return true;
    }
    return false;
}

$role = auth()->user()->role ?? 'user';

// Menu berdasarkan role
if ($role == 'administrator') {
    $menus = [
        item('Dashboard', 'dashboard', 'fas fa-tachometer-alt'),
        item('Master User', '', 'fas fa-users-cog', ['hasSubmenu' => true, 'submenu' => [
            item('Administrator', 'master_user.administrator.index', 'fas fa-user-shield'),
            item('Admin', 'master_user.admin.index', 'fas fa-user-tie'),
            item('Teknisi', 'master_user.teknisi.index', 'fas fa-tools'),
            item('User', 'master_user.user.index', 'fas fa-users'),
        ]]),
        item('Master Data', '', 'fas fa-database', ['hasSubmenu' => true, 'submenu' => [
            item('Departemen', 'departemen.index', 'fas fa-building'),
            item('Urgency Level', 'urgency.index', 'fas fa-exclamation-triangle'),
        ]]),
        item('Tiket', '', 'fas fa-ticket-alt', ['hasSubmenu' => true, 'submenu' => [
            item('Daftar Tiket', 'tiket.index', 'fas fa-list'),
        ]]),
        item('C4.5 Machine Learning', '', 'fas fa-brain', ['hasSubmenu' => true, 'submenu' => [
            item('Training Model', 'tiket.training.index', 'fas fa-graduation-cap'),
            item('Pohon Keputusan', 'tiket.training.tree', 'fas fa-sitemap'),
            item('Pola & Rules', 'tiket.training.rules', 'fas fa-list-ol'),
            item('Analisis & Pola', 'tiket.analytics', 'fas fa-chart-line'),
        ]]),
        item('Laporan', '', 'fas fa-file-alt', ['hasSubmenu' => true, 'submenu' => [
            item('Laporan Tiket', 'tiket.laporan', 'fas fa-table'),
        ]]),
        item('Profil Saya', 'ganti_profil.index', 'fas fa-user-circle'),
    ];

} elseif ($role == 'admin') {
    $menus = [
        item('Dashboard', 'dashboard', 'fas fa-tachometer-alt'),
        item('Manajemen Tiket', '', 'fas fa-ticket-alt', ['hasSubmenu' => true, 'submenu' => [
            item('Daftar Tiket', 'tiket.index', 'fas fa-list'),
            item('Analisis & Pola', 'tiket.analytics', 'fas fa-chart-line'),
        ]]),
        item('Laporan', '', 'fas fa-file-alt', ['hasSubmenu' => true, 'submenu' => [
            item('Laporan Tiket', 'tiket.laporan', 'fas fa-table'),
        ]]),
        item('Profil Saya', 'ganti_profil.index', 'fas fa-user-circle'),
    ];
} elseif ($role == 'teknisi') {
    $menus = [
        item('Dashboard', 'dashboard', 'fas fa-tachometer-alt'),
        item('Tiket Saya', 'tiket.index', 'fas fa-tasks'),
        item('Laporan Kerja', 'tiket.laporan', 'fas fa-clipboard-list'),
        item('Profil Saya', 'ganti_profil.index', 'fas fa-user-circle'),
    ];
} else { // user
    $menus = [
        item('Dashboard', 'dashboard', 'fas fa-tachometer-alt'),
        item('Tiket Saya', 'tiket.index', 'fas fa-ticket-alt'),
        item('Riwayat Tiket', 'tiket.laporan', 'fas fa-history'),
        item('Profil Saya', 'ganti_profil.index', 'fas fa-user-circle'),
    ];
}
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    {{-- Brand Logo --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('public/templates/dist/img/Logo_HelpDesk.png') }}" 
             alt="Helpdesk Logo" 
             class="brand-image img-circle elevation-3">
        <span class="brand-text">Helpdesk System</span>
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">
        {{-- Sidebar Menu --}}
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" 
                data-widget="treeview" 
                role="menu" 
                data-accordion="false">
                
                @foreach($menus as $menu)
                    @if(isset($menu->hasSubmenu) && $menu->hasSubmenu)
                        {{-- Menu dengan Submenu --}}
                        <li class="nav-item {{ isActiveParent($menu->submenu) ? 'menu-open' : '' }}">
                            <a href="#" 
                               class="nav-link {{ isActiveParent($menu->submenu) ? 'active' : '' }}"
                               data-title="{{ $menu->title }}">
                                <i class="nav-icon {{ $menu->icon }}"></i>
                                <p>
                                    {{ $menu->title }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach($menu->submenu as $submenu)
                                    <li class="nav-item">
                                        <a href="{{ linkFor($submenu->path) }}" 
                                           class="nav-link {{ isActive($submenu->path) ? 'active' : '' }}"
                                           data-title="{{ $submenu->title }}">
                                            <i class="nav-icon {{ $submenu->icon }}"></i>
                                            <p>{{ $submenu->title }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        {{-- Menu Tanpa Submenu --}}
                        <li class="nav-item">
                            <a href="{{ linkFor($menu->path) }}" 
                               class="nav-link {{ isActive($menu->path) ? 'active' : '' }}"
                               data-title="{{ $menu->title }}">
                                <i class="nav-icon {{ $menu->icon }}"></i>
                                <p>{{ $menu->title }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach

                {{-- Logout Button --}}
                <li class="nav-item mt-3">
                    <a href="#" 
                       class="nav-link bg-danger" 
                       data-title="Logout"
                       onclick="event.preventDefault(); if(confirm('Yakin ingin logout?')) document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

{{-- Logout Form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
