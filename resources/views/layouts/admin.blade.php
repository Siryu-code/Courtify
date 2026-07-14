<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Courtify Arena | @yield('title', 'Dashboard')</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Chart.js (hanya untuk dashboard) --}}
    @stack('head-scripts')

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            display: flex;
            min-height: 100vh;
        }

        /* ========== SIDEBAR ========== */
        .admin-sidebar {
            width: 280px;
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
            transition: transform 0.3s;
        }

        /* Mini Profile */
        .sidebar-profile {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
        }
        .sidebar-profile:hover { background: #f9fafb; }
        .sidebar-avatar {
            width: 48px; height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f3f4f6;
            flex-shrink: 0;
        }
        .sidebar-avatar-placeholder {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: #0d6efd;
            color: #fff;
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-profile-name {
            font-weight: 700;
            font-size: 16px;
            color: #111827;
        }
        .sidebar-profile-sub {
            font-size: 12px;
            color: #6b7280;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 12px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
            font-size: 15px;
            transition: 0.15s;
        }
        .sidebar-nav-item:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .sidebar-nav-item.active {
            background: #0d6efd;
            color: #fff;
        }
        .sidebar-nav-item i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        /* Logout */
        .sidebar-logout {
            padding: 12px 12px 20px;
            border-top: 1px solid #f3f4f6;
        }
        .sidebar-logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            font-size: 15px;
            transition: 0.15s;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
        }
        .sidebar-logout-btn:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* ========== MAIN CONTENT ========== */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
        }

        /* ========== MOBILE ========== */
        .admin-mobile-header {
            display: none;
            background: #fff;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-hamburger-btn {
            background: none;
            border: none;
            font-size: 22px;
            color: #374151;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        .admin-mobile-title {
            font-weight: 700;
            font-size: 17px;
            color: #000;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.open {
                display: block;
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-mobile-header {
                display: flex;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    {{-- Overlay Mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        {{-- Mini Profile --}}
        <a href="{{ route('profile') }}" class="sidebar-profile">
            @if(Auth::user()->profile_photo)
                <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Admin" class="sidebar-avatar">
            @else
                <div class="sidebar-avatar-placeholder">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <div class="sidebar-profile-name">{{ Auth::user()->name }}</div>
                <div class="sidebar-profile-sub">Courtify Arena Management</div>
            </div>
        </a>

        {{-- Navigation (5 items) --}}
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-table-cells"></i> Dashboard
            </a>
            <a href="{{ route('admin.bookings') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> Bookings
            </a>
            <a href="{{ route('admin.venues.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.venues*') ? 'active' : '' }}">
                <i class="fa-solid fa-futbol"></i> Courts
            </a>
            <a href="{{ route('admin.promos.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.promos*') ? 'active' : '' }}">
                <i class="fa-solid fa-tag"></i> Promo
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="sidebar-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Users
            </a>
        </nav>

        {{-- Logout --}}
        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="admin-main">
        {{-- Mobile Header --}}
        <div class="admin-mobile-header">
            <button class="admin-hamburger-btn" id="mobileMenuToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="admin-mobile-title">Admin Panel</span>
        </div>

        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Mobile Toggle --}}
    <script>
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('mobileMenuToggle');
        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    </script>

    @stack('scripts')
</body>
</html>