<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Pure Wear Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        * { --admin-sidebar-width: 240px; --admin-transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
        
        html, body { height: 100%; }
        body { display: flex; flex-direction: column; }
        
        .admin-wrapper {
            display: flex;
            flex: 1;
            overflow-x: hidden; /* tránh tràn ngang nhưng vẫn cho phép scroll dọc */
        }
        
        .sidebar {
            background-color: #343a40;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform var(--admin-transition);
            flex-shrink: 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            transition: color 0.2s ease, padding-left 0.2s ease;
        }
        
        .sidebar .nav-link.active {
            color: #ffd700;
            font-weight: 600;
            border-left: 3px solid #ffd700;
            padding-left: calc(0.75rem - 3px);
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            padding-left: 1rem;
        }
        
        /* Desktop layout - sidebar always visible */
        @media (min-width: 992px) {
            .sidebar {
                position: relative;
                width: 240px;
                min-height: auto;
            }
            
            main.admin-main {
                flex: 1;
                overflow-y: auto;
                padding: 2rem !important;
            }
        }
        
        /* Mobile: hamburger & off-canvas */
        @media (max-width: 991.98px) {
            .admin-wrapper {
                flex-direction: column;
            }
            
            .sidebar {
                position: fixed;
                top: 56px;
                left: 0;
                width: var(--admin-sidebar-width);
                height: calc(100vh - 56px);
                z-index: 1040;
                transform: translate3d(-100%,0,0);
                box-shadow: 2px 0 8px rgba(0,0,0,0.15);
                will-change: transform;
            }
            
            .sidebar.show {
                transform: translate3d(0,0,0);
            }
            
            body { position: relative; overflow-x: hidden; }
            
            .admin-navbar {
                min-height: 56px;
                display: flex;
                align-items: center;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                z-index: 1050;
                position: sticky;
                top: 0;
                order: -1;
            }
            
            .admin-navbar-toggler {
                padding: 0.5rem 0.75rem;
                border: none;
                background: none;
                color: rgba(255,255,255,0.85);
                cursor: pointer;
                transition: color 0.2s ease, transform 0.2s ease;
                font-size: 1.5rem;
                line-height: 1;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .admin-navbar-toggler:hover { color: #fff; }
            #adminMenuIcon { display:inline-block; width:1.25em; text-align:center; }
            .admin-navbar-toggler.active #adminMenuIcon { transform: rotate(90deg); }
            
            .admin-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1035;
                opacity: 0;
                pointer-events: none;
                transition: opacity var(--admin-transition);
            }
            
            .admin-backdrop.show {
                opacity: 1;
                pointer-events: auto;
            }
            
            main.admin-main {
                width: 100%;
                padding: 1rem 1rem calc(70px + 1rem) !important; /* leave space for bottom quick nav */
                order: 1;
                flex: 1;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch; /* mượt hơn trên iOS */
            }
            
            .sidebar .nav-link {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
            
            .sidebar h5 {
                margin-bottom: 0.75rem;
                font-size: 1rem;
            }
        }
        
        /* Custom Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 30px;
        }
        
        .pagination .page-item {
            list-style: none;
        }
        
        .pagination .page-link {
            position: relative;
            display: block;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            min-width: 45px;
            text-align: center;
        }
        
        .pagination .page-link:hover {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }
        
        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            pointer-events: none;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            opacity: 0.6;
        }
        
        .pagination .page-link:focus {
            z-index: 3;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            font-weight: 600;
            padding: 10px 20px;
        }
        
        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 8px 12px;
                font-size: 13px;
                min-width: 38px;
            }
            
            .pagination .page-item:first-child .page-link,
            .pagination .page-item:last-child .page-link {
                padding: 8px 14px;
            }
        }
        
        /* Table improvements */
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transition: background-color 0.2s ease;
        }
        
        .btn-group .btn {
            transition: all 0.2s ease;
        }
        
        .btn-group .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
            /* Bottom quick navigation */
            .admin-mobile-quick {
                position: fixed;
                bottom: 0; left: 0; right: 0;
                height: 60px;
                background:#1f2327;
                display:flex;
                justify-content:space-around;
                align-items:center;
                z-index:1050;
                border-top:1px solid #2e3439;
                box-shadow:0 -2px 6px rgba(0,0,0,.25);
                padding-bottom: env(safe-area-inset-bottom);
            }
            .admin-mobile-quick a {
                color:#cfd3d6; text-decoration:none; font-size:.75rem; display:flex; flex-direction:column; align-items:center; gap:4px; width:20%; padding:6px 0; border-radius:6px; transition:background .2s,color .2s;
            }
            .admin-mobile-quick a.active, .admin-mobile-quick a:active, .admin-mobile-quick a:hover { color:#fff; background:#30363c; }
            .admin-mobile-quick a i { font-size:1rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            .sidebar, .admin-backdrop { transition:none !important; }
        }
    </style>
</head>
<body>
    <!-- Mobile navbar (hidden on desktop) -->
    <nav class="navbar navbar-dark bg-dark d-lg-none admin-navbar">
        <button id="adminMenuToggle" class="admin-navbar-toggler" type="button" aria-label="Toggle navigation" aria-expanded="false"><span id="adminMenuIcon">☰</span></button>
        <span class="navbar-brand mb-0 h6 ms-2">Pure Wear Admin</span>
    </nav>
    
    <!-- Backdrop overlay for mobile -->
    <div id="adminBackdrop" class="admin-backdrop"></div>
    
    <!-- Admin wrapper (flexbox container) -->
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div id="adminSidebar" class="sidebar d-lg-block">
            <div class="position-sticky pt-3" style="top: 0;">
                <div class="text-center mb-4 d-none d-lg-block">
                    <h5 class="text-white">Pure Wear Admin</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <i class="fas fa-box me-2"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users me-2"></i>Người dùng
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="admin-main" id="adminMain">
            @yield('content')
        </main>
    </div>

    <!-- Mobile quick nav -->
    <nav class="admin-mobile-quick d-lg-none" aria-label="Điều hướng nhanh">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-quick-link>
            <i class="fas fa-home"></i><span>Dash</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" data-quick-link>
            <i class="fas fa-box"></i><span>SP</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" data-quick-link>
            <i class="fas fa-shopping-cart"></i><span>ĐH</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-quick-link>
            <i class="fas fa-users"></i><span>User</span>
        </a>
        <a href="#" id="adminQuickMenu" aria-label="Mở menu" data-quick-link>
            <i class="fas fa-bars"></i><span>Menu</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminBackdrop');
            const toggler = document.getElementById('adminMenuToggle');
            const quickMenu = document.getElementById('adminQuickMenu');
            const LS_KEY = 'adminSidebarOpen';
            const mq = window.matchMedia('(max-width: 991.98px)');
            let focusTrapEnabled = false;
            
            // Sync state between sidebar and UI elements
            function updateUI(isOpen) {
                if (mq.matches) {
                    // Mobile mode
                    if (isOpen) {
                        sidebar.classList.add('show');
                        backdrop.classList.add('show');
                        toggler.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    } else {
                        sidebar.classList.remove('show');
                        backdrop.classList.remove('show');
                        toggler.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                } else {
                    // Desktop mode: reset all
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                    toggler.classList.remove('active');
                    document.body.style.overflow = '';
                }
                // Icon & ARIA sync
                toggler.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                const icon = document.getElementById('adminMenuIcon');
                if(icon){ icon.textContent = isOpen ? '✕' : '☰'; }
                if(isOpen) { enableFocusTrap(); } else { disableFocusTrap(); }
            }
            
            // Restore state on page load
            function restoreState() {
                const wasOpen = localStorage.getItem(LS_KEY) === '1';
                updateUI(wasOpen && mq.matches);
            }
            
            // Toggle handler
            function handleToggle() {
                const isOpen = sidebar.classList.contains('show');
                const newState = !isOpen;
                // Do not persist open state for mobile to avoid flicker across pages
                if(!mq.matches) { localStorage.setItem(LS_KEY,'0'); }
                updateUI(newState);
            }

            function enableFocusTrap(){
                if(focusTrapEnabled) return; focusTrapEnabled = true;
                const focusables = sidebar.querySelectorAll('a, button');
                if(!focusables.length) return;
                const first = focusables[0];
                const last = focusables[focusables.length-1];
                first.focus();
                function trap(e){
                    if(e.key === 'Escape'){ updateUI(false); }
                    if(e.key === 'Tab'){
                        if(e.shiftKey && document.activeElement === first){ e.preventDefault(); last.focus(); }
                        else if(!e.shiftKey && document.activeElement === last){ e.preventDefault(); first.focus(); }
                    }
                }
                document.addEventListener('keydown', trap);
                sidebar._trapHandler = trap;
            }
            function disableFocusTrap(){
                if(!focusTrapEnabled) return; focusTrapEnabled = false;
                if(sidebar._trapHandler){ document.removeEventListener('keydown', sidebar._trapHandler); sidebar._trapHandler=null; }
            }
            
            // Close on backdrop click
            function handleBackdropClick(e) {
                if (e.target === backdrop) {
                    localStorage.setItem(LS_KEY, '0');
                    updateUI(false);
                }
            }
            
            // Close when clicking sidebar links (on mobile)
            function handleSidebarLinkClick(e) {
                if (e.target.tagName === 'A' || e.target.closest('a')) {
                    if (mq.matches) {
                        localStorage.setItem(LS_KEY, '0');
                        updateUI(false);
                    }
                }
            }
            
            // Handle media query change
            function handleMediaChange(e) {
                if (!e.matches) {
                    localStorage.setItem(LS_KEY, '0');
                }
                updateUI(e.matches && localStorage.getItem(LS_KEY) === '1');
            }
            
            // Attach listeners
            toggler?.addEventListener('click', handleToggle);
            quickMenu?.addEventListener('click', function(e){ e.preventDefault(); handleToggle(); });
            backdrop?.addEventListener('click', handleBackdropClick);
            sidebar?.addEventListener('click', handleSidebarLinkClick);
            mq.addEventListener('change', handleMediaChange);
            
            // Initial state (always closed on mobile to prevent content shift)
            function init(){ localStorage.setItem(LS_KEY,'0'); restoreState(); }
            if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
        })();
    </script>
    @yield('scripts')
</body>
</html>