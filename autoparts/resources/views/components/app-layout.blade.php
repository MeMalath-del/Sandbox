@props(['title' => null])

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}AutoParts Hub - متجر قطع الغيار</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #f59e0b;
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
        
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
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
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: #ef4444;
            color: white;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    @livewireStyles
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ url('/') }}">
                <i class="bi bi-gear-wide-connected me-2"></i>
                AutoParts Hub
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="search-box mx-auto" style="max-width: 500px; width: 100%;">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="q" class="form-control" placeholder="ابحث عن قطع الغيار...">
                        <i class="bi bi-search search-icon"></i>
                    </form>
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
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span class="notification-badge">0</span>
                            </a>
                        </li>
                        
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="notification-badge">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                                <li class="text-center py-4 text-muted">
                                    <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                                    لا توجد إشعارات جديدة
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="avatar me-2">
                                <span>{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-2"></i> طلباتي</a></li>
                                <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2"></i> المفضلة</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @if(auth()->user()->isStore())
                                <li><a class="dropdown-item" href="{{ route('store.dashboard') }}"><i class="bi bi-shop me-2"></i> لوحة تحكم المتجر</a></li>
                                @endif
                                @if(auth()->user()->isDriver())
                                <li><a class="dropdown-item" href="{{ route('driver.dashboard') }}"><i class="bi bi-truck me-2"></i> لوحة تحكم الموصل</a></li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> لوحة الإدارة</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <main>
        {{ $slot }}
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-gear-wide-connected me-2"></i>AutoParts Hub</h5>
                    <p class="text-muted">منصة متكاملة لقطع غيار السيارات</p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">روابط سريعة</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/" class="text-muted text-decoration-none">الرئيسية</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}" class="text-muted text-decoration-none">المنتجات</a></li>
                        <li class="mb-2"><a href="{{ route('stores.index') }}" class="text-muted text-decoration-none">المتاجر</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">الدعم</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">مركز المساعدة</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">تواصل معنا</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">تواصل معنا</h6>
                    <p class="text-muted"><i class="bi bi-telephone me-2"></i> 920000000</p>
                    <p class="text-muted"><i class="bi bi-envelope me-2"></i> info@autoparts.sa</p>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center text-muted mb-0">© {{ date('Y') }} AutoParts Hub. جميع الحقوق محفوظة</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
