<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'لوحة التحكم' }} - AutoParts Hub</title>
    
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #1a56db;
            --primary-dark: #1e40af;
            --sidebar-bg: #1f2937;
            --sidebar-hover: #374151;
        }
        
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f3f4f6;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: transform 0.3s;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-brand i {
            color: #f59e0b;
            font-size: 1.8rem;
        }
        
        .sidebar-nav {
            padding: 20px 15px;
        }
        
        .nav-section {
            margin-bottom: 25px;
        }
        
        .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 15px;
            margin-bottom: 10px;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.2rem;
            width: 24px;
        }
        
        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }
        
        .sidebar-nav .nav-link.active {
            background: var(--primary-color);
            color: white;
        }
        
        .sidebar-nav .nav-link .badge {
            margin-right: auto;
        }
        
        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Header */
        .dashboard-header {
            background: white;
            height: var(--header-height);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-search {
            position: relative;
            width: 300px;
        }
        
        .header-search input {
            border-radius: 10px;
            padding-right: 40px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .header-search input:focus {
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
        }
        
        .header-search i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            border: none;
            background: #f3f4f6;
            color: #6b7280;
            position: relative;
            transition: all 0.3s;
        }
        
        .header-icon-btn:hover {
            background: #e5e7eb;
            color: var(--primary-color);
        }
        
        .header-icon-btn .badge {
            position: absolute;
            top: -5px;
            left: -5px;
            font-size: 0.65rem;
            padding: 4px 6px;
        }
        
        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            padding: 5px 10px;
            border-radius: 10px;
        }
        
        .user-dropdown .dropdown-toggle:hover {
            background: #f3f4f6;
        }
        
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .user-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .user-dropdown .user-info {
            text-align: right;
        }
        
        .user-dropdown .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-dropdown .user-role {
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        /* Page Content */
        .page-content {
            padding: 30px;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px;
            font-weight: 600;
        }
        
        /* Toast */
        .toast-container {
            position: fixed;
            top: 90px;
            left: 30px;
            z-index: 9999;
        }
        
        .custom-toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .custom-toast.success {
            border-right: 4px solid #10b981;
        }
        
        .custom-toast.error {
            border-right: 4px solid #ef4444;
        }
        
        .custom-toast i {
            font-size: 1.5rem;
        }
        
        .custom-toast.success i {
            color: #10b981;
        }
        
        .custom-toast.error i {
            color: #ef4444;
        }
        
        /* Mobile Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #1f2937;
        }
        
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <i class="bi bi-gear-wide-connected"></i>
                AutoParts Hub
            </a>
        </div>
        
        <nav class="sidebar-nav">
            @if(auth()->user()->isAdmin())
            <!-- Admin Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">الرئيسية</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">إدارة المحتوى</div>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> المستخدمين
                </a>
                <a href="{{ route('admin.stores') }}" class="nav-link {{ request()->routeIs('admin.stores*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i> المتاجر
                    @if($pendingStores = \App\Models\Store::where('status', 'pending')->count())
                    <span class="badge bg-warning">{{ $pendingStores }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.drivers') }}" class="nav-link {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> الموصلين
                </a>
                <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> المنتجات
                    @if($pendingProducts = \App\Models\Product::where('status', 'pending')->count())
                    <span class="badge bg-warning">{{ $pendingProducts }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i> التصنيفات
                </a>
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                    <i class="bi bi-award"></i> الماركات
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">المعاملات</div>
                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> الطلبات
                </a>
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> التقارير
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">الإعدادات</div>
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> الإعدادات
                </a>
            </div>
            
            @elseif(auth()->user()->isStore())
            <!-- Store Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">الرئيسية</div>
                <a href="{{ route('store.dashboard') }}" class="nav-link {{ request()->routeIs('store.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">إدارة المتجر</div>
                <a href="{{ route('store.products.index') }}" class="nav-link {{ request()->routeIs('store.products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> المنتجات
                </a>
                <a href="{{ route('store.orders.index') }}" class="nav-link {{ request()->routeIs('store.orders*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> الطلبات
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">الإعدادات</div>
                <a href="{{ route('store.settings') }}" class="nav-link {{ request()->routeIs('store.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> إعدادات المتجر
                </a>
            </div>
            
            @elseif(auth()->user()->isDriver())
            <!-- Driver Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">الرئيسية</div>
                <a href="{{ route('driver.dashboard') }}" class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">التوصيلات</div>
                <a href="{{ route('driver.deliveries') }}" class="nav-link {{ request()->routeIs('driver.deliveries*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> توصيلاتي
                </a>
                <a href="{{ route('driver.earnings') }}" class="nav-link {{ request()->routeIs('driver.earnings') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> الأرباح
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">الإعدادات</div>
                <a href="{{ route('driver.settings') }}" class="nav-link {{ request()->routeIs('driver.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> الإعدادات
                </a>
            </div>
            @endif
            
            <div class="nav-section mt-4">
                <a href="/" class="nav-link">
                    <i class="bi bi-house"></i> الموقع الرئيسي
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-search d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="بحث...">
                </div>
            </div>
            
            <div class="header-actions">
                <button class="header-icon-btn" title="الإشعارات">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <button class="header-icon-btn" title="الرسائل">
                    <i class="bi bi-chat-dots"></i>
                    <span class="badge bg-primary rounded-pill">5</span>
                </button>
                
                <div class="dropdown user-dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                        <div class="user-info d-none d-md-block">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->user_type }}</div>
                        </div>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> الملف الشخصي</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> الإعدادات</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        
        <!-- Toast Container -->
        <div class="toast-container">
            @if(session('success'))
            <div class="custom-toast success">
                <i class="bi bi-check-circle-fill"></i>
                <div>
                    <strong>تم بنجاح</strong>
                    <p class="mb-0 small">{{ session('success') }}</p>
                </div>
            </div>
            @endif
            @if(session('error'))
            <div class="custom-toast error">
                <i class="bi bi-x-circle-fill"></i>
                <div>
                    <strong>خطأ</strong>
                    <p class="mb-0 small">{{ session('error') }}</p>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Page Content -->
        <div class="page-content">
            {{ $slot }}
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        
        // Auto hide toasts
        setTimeout(() => {
            document.querySelectorAll('.custom-toast').forEach(toast => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
