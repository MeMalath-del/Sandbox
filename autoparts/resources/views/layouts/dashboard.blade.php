<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'لوحة التحكم' }} - AutoParts Hub</title>

    @if(app()->getLocale() == 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f1f5f9;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            background: white;
            box-shadow: -1px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .sidebar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-item {
            margin: 0.25rem 0.75rem;
        }
        
        .sidebar-nav .nav-link {
            color: #4b5563;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }
        
        .sidebar-nav .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
            width: 24px;
        }
        
        .nav-section-title {
            padding: 0.5rem 1.75rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 600;
            letter-spacing: 0.05em;
            margin-top: 1rem;
        }
        
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-navbar {
            background: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .content-wrapper {
            padding: 1.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-card .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        .stat-card .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .stat-card .stat-change {
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
        }
    </style>
    
    @livewireStyles
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <i class="bi bi-gear-wide-connected me-2"></i>
                AutoParts Hub
            </a>
        </div>
        
        <nav class="sidebar-nav">
            {{ $sidebar ?? '' }}
        </nav>
        
        <!-- User Info at Bottom -->
        <div class="border-top p-3 mt-auto">
            <div class="d-flex align-items-center">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="avatar me-3">
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-lg-none me-3" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="mb-0">{{ $header ?? 'لوحة التحكم' }}</h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5 text-secondary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            3
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold">الإشعارات</span>
                            <a href="#" class="text-primary small">عرض الكل</a>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                            لا توجد إشعارات جديدة
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <a href="/" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-house me-1"></i> الرئيسية
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            {{ $slot }}
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
