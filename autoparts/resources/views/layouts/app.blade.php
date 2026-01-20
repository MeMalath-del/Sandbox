<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'AutoParts Hub' }} - متجر قطع الغيار</title>

    <!-- Bootstrap 5 RTL -->
    @if(app()->getLocale() == 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts - Tajawal for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f3f4f6;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8fafc;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 0.75rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: white;
            box-shadow: 1px 0 3px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #4b5563;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 24px;
        }
        
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar-lg {
            width: 80px;
            height: 80px;
        }
        
        .stat-card {
            border-radius: 1rem;
            padding: 1.5rem;
        }
        
        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .table th {
            font-weight: 600;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding-right: 45px;
            border-radius: 50px;
        }
        
        .search-box .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 0.75rem;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: var(--danger-color);
            color: white;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
    
    @livewireStyles
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand text-primary" href="{{ url('/') }}">
                <i class="bi bi-gear-wide-connected me-2"></i>
                AutoParts Hub
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Box -->
                <div class="search-box mx-auto" style="max-width: 500px; width: 100%;">
                    <input type="text" class="form-control" placeholder="ابحث عن قطع الغيار...">
                    <i class="bi bi-search search-icon"></i>
                </div>
                
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> تسجيل الدخول
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> إنشاء حساب
                            </a>
                        </li>
                    @else
                        <!-- Cart -->
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span class="notification-badge">0</span>
                            </a>
                        </li>
                        
                        <!-- Notifications -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="notification-badge">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">الإشعارات</span>
                                    <a href="#" class="text-primary small">تحديد الكل كمقروء</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center py-4 text-muted">
                                    <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                                    لا توجد إشعارات جديدة
                                </li>
                            </ul>
                        </li>
                        
                        <!-- User Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="avatar me-2">
                                <span>{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="bi bi-person me-2"></i> الملف الشخصي
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag me-2"></i> طلباتي
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                        <i class="bi bi-heart me-2"></i> المفضلة
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @if(auth()->user()->isStore())
                                <li>
                                    <a class="dropdown-item" href="{{ route('store.dashboard') }}">
                                        <i class="bi bi-shop me-2"></i> لوحة تحكم المتجر
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->isDriver())
                                <li>
                                    <a class="dropdown-item" href="{{ route('driver.dashboard') }}">
                                        <i class="bi bi-truck me-2"></i> لوحة تحكم الموصل
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> لوحة الإدارة
                                    </a>
                                </li>
                                @endif
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
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-gear-wide-connected me-2"></i>
                        AutoParts Hub
                    </h5>
                    <p class="text-muted">
                        منصة متكاملة لقطع غيار السيارات تربط بين المشترين والمتاجر والموصلين
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">روابط سريعة</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">الرئيسية</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">المنتجات</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">المتاجر</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">العروض</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">خدماتنا</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">سجل كمتجر</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">سجل كموصل</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">برنامج الشركاء</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">API للمطورين</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">الدعم</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">مركز المساعدة</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">الأسئلة الشائعة</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">تواصل معنا</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">سياسة الإرجاع</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">تواصل معنا</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> 920000000</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> info@autoparts.sa</li>
                        <li class="mb-2"><i class="bi bi-whatsapp me-2"></i> واتساب</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0">© {{ date('Y') }} AutoParts Hub. جميع الحقوق محفوظة</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" height="25" class="me-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" height="25" class="me-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" height="25">
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
