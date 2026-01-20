<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AutoParts Hub' }} - متجر قطع الغيار</title>
    
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --primary-color: #1a56db;
            --primary-dark: #1e40af;
            --secondary-color: #f97316;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-bg: #f8fafc;
            --border-color: #e5e7eb;
        }
        
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navbar Styles */
        .navbar-main {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .navbar-top {
            background: var(--dark-color);
            padding: 8px 0;
            font-size: 0.85rem;
        }
        
        .navbar-top a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .navbar-top a:hover {
            color: white;
        }
        
        .navbar-middle {
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            font-size: 2rem;
            color: var(--secondary-color);
        }
        
        .search-box {
            position: relative;
            flex: 1;
            max-width: 600px;
        }
        
        .search-box input {
            border-radius: 50px;
            padding: 12px 50px 12px 25px;
            border: none;
            font-size: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .search-box button {
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border: none;
            color: white;
        }
        
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .search-suggestions.show {
            display: block;
        }
        
        .search-suggestion-item {
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .search-suggestion-item:hover {
            background: var(--light-bg);
        }
        
        .search-suggestion-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-icon-btn {
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.8rem;
            position: relative;
            transition: transform 0.3s;
        }
        
        .nav-icon-btn:hover {
            color: white;
            transform: translateY(-3px);
        }
        
        .nav-icon-btn i {
            font-size: 1.5rem;
            margin-bottom: 3px;
        }
        
        .nav-icon-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 4px 7px;
            background: var(--secondary-color);
        }
        
        .navbar-categories {
            background: rgba(0,0,0,0.2);
            padding: 10px 0;
        }
        
        .category-nav {
            display: flex;
            gap: 5px;
            list-style: none;
            margin: 0;
            padding: 0;
            overflow-x: auto;
        }
        
        .category-nav::-webkit-scrollbar {
            display: none;
        }
        
        .category-nav a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            white-space: nowrap;
            transition: all 0.3s;
        }
        
        .category-nav a:hover,
        .category-nav a.active {
            background: white;
            color: var(--primary-color);
        }
        
        /* Product Card */
        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .product-card .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .product-card .card-img-wrapper {
            overflow: hidden;
            position: relative;
        }
        
        .product-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 2;
        }
        
        .product-badges .badge {
            padding: 6px 12px;
            font-size: 0.75rem;
            border-radius: 20px;
        }
        
        .product-actions {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.3s;
            z-index: 2;
        }
        
        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }
        
        .product-actions .btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .product-actions .btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .product-card .card-body {
            padding: 20px;
        }
        
        .product-card .store-name {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .product-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            line-height: 1.4;
            height: 2.8em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .product-card .card-title a {
            color: var(--dark-color);
            text-decoration: none;
        }
        
        .product-card .card-title a:hover {
            color: var(--primary-color);
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px;
            font-size: 0.85rem;
        }
        
        .product-rating .stars {
            color: #fbbf24;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .product-price .current {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .product-price .old {
            font-size: 0.9rem;
            color: #9ca3af;
            text-decoration: line-through;
        }
        
        .product-price .discount {
            background: #fef2f2;
            color: var(--danger-color);
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .add-to-cart-btn {
            width: 100%;
            margin-top: 15px;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .add-to-cart-btn:hover {
            transform: scale(1.02);
        }
        
        /* Section Headers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .section-header h2 {
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-header h2 i {
            color: var(--primary-color);
        }
        
        .section-header .view-all {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .section-header .view-all:hover {
            gap: 10px;
        }
        
        /* Footer */
        footer {
            background: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
            margin-top: auto;
        }
        
        footer h5 {
            font-weight: 700;
            margin-bottom: 25px;
            color: white;
        }
        
        footer ul {
            list-style: none;
            padding: 0;
        }
        
        footer ul li {
            margin-bottom: 12px;
        }
        
        footer ul a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        footer ul a:hover {
            color: white;
            padding-right: 5px;
        }
        
        .footer-contact i {
            width: 30px;
            color: var(--secondary-color);
        }
        
        .social-links {
            display: flex;
            gap: 10px;
        }
        
        .social-links a {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-5px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 40px;
            padding-top: 30px;
            text-align: center;
            color: rgba(255,255,255,0.6);
        }
        
        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }
        
        .payment-methods img {
            height: 30px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }
        
        .payment-methods img:hover {
            opacity: 1;
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 100px;
            left: 20px;
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
            border-right: 4px solid var(--success-color);
        }
        
        .custom-toast.error {
            border-right: 4px solid var(--danger-color);
        }
        
        .custom-toast i {
            font-size: 1.5rem;
        }
        
        .custom-toast.success i {
            color: var(--success-color);
        }
        
        .custom-toast.error i {
            color: var(--danger-color);
        }
        
        /* Quick View Modal */
        .quick-view-modal .modal-content {
            border-radius: 20px;
            border: none;
        }
        
        .quick-view-modal .modal-body {
            padding: 30px;
        }
        
        /* Compare Bar */
        .compare-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -5px 30px rgba(0,0,0,0.1);
            padding: 15px;
            z-index: 1000;
            transform: translateY(100%);
            transition: transform 0.3s;
        }
        
        .compare-bar.show {
            transform: translateY(0);
        }
        
        .compare-items {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .compare-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--light-bg);
            padding: 10px 15px;
            border-radius: 10px;
        }
        
        .compare-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .navbar-middle {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-box {
                max-width: 100%;
                order: 3;
            }
            
            .nav-icons {
                gap: 15px;
            }
        }
        
        @media (max-width: 767px) {
            .navbar-brand {
                font-size: 1.3rem;
            }
            
            .nav-icon-btn span {
                display: none;
            }
            
            .product-card .card-img-top {
                height: 150px;
            }
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 5px 20px rgba(26, 86, 219, 0.3);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 999;
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            transform: translateY(-5px);
            background: var(--primary-dark);
        }
    </style>
    
    @livewireStyles
</head>
<body>
    <!-- Top Bar -->
    <div class="navbar-top">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-4">
                    <a href="tel:+966500000000"><i class="bi bi-telephone me-1"></i> 966500000000+</a>
                    <a href="mailto:info@autoparts.sa"><i class="bi bi-envelope me-1"></i> info@autoparts.sa</a>
                </div>
                <div class="d-flex gap-3">
                    <a href="#"><i class="bi bi-truck me-1"></i> تتبع طلبك</a>
                    <a href="#"><i class="bi bi-question-circle me-1"></i> المساعدة</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navbar -->
    <nav class="navbar-main sticky-top">
        <div class="navbar-middle">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <!-- Logo -->
                    <a class="navbar-brand" href="/">
                        <i class="bi bi-gear-wide-connected"></i>
                        AutoParts Hub
                    </a>
                    
                    <!-- Search -->
                    <div class="search-box">
                        <form action="{{ route('search') }}" method="GET">
                            <input type="text" name="q" class="form-control" placeholder="ابحث عن قطع غيار، رقم OEM، موديل السيارة..." id="searchInput" autocomplete="off">
                            <button type="submit"><i class="bi bi-search"></i></button>
                        </form>
                        <div class="search-suggestions" id="searchSuggestions"></div>
                    </div>
                    
                    <!-- Nav Icons -->
                    <div class="nav-icons">
                        @auth
                        <a href="{{ route('wishlist.index') }}" class="nav-icon-btn">
                            <i class="bi bi-heart"></i>
                            <span>المفضلة</span>
                            <span class="badge rounded-pill">{{ auth()->user()->wishlists()->count() }}</span>
                        </a>
                        <a href="{{ route('cart.index') }}" class="nav-icon-btn">
                            <i class="bi bi-cart3"></i>
                            <span>السلة</span>
                            @php $cartCount = auth()->user()->cart?->items?->count() ?? 0; @endphp
                            @if($cartCount > 0)
                            <span class="badge rounded-pill">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown">
                            <a href="#" class="nav-icon-btn dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ auth()->user()->first_name ?? 'حسابي' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i> الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-2"></i> طلباتي</a></li>
                                <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2"></i> المفضلة</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.wallet') }}"><i class="bi bi-wallet2 me-2"></i> المحفظة</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> لوحة الإدارة</a></li>
                                @elseif(auth()->user()->isStore())
                                <li><a class="dropdown-item text-primary" href="{{ route('store.dashboard') }}"><i class="bi bi-shop me-2"></i> لوحة المتجر</a></li>
                                @elseif(auth()->user()->isDriver())
                                <li><a class="dropdown-item text-primary" href="{{ route('driver.dashboard') }}"><i class="bi bi-truck me-2"></i> لوحة الموصل</a></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="nav-icon-btn">
                            <i class="bi bi-box-arrow-in-left"></i>
                            <span>تسجيل الدخول</span>
                        </a>
                        <a href="{{ route('register') }}" class="nav-icon-btn">
                            <i class="bi bi-person-plus"></i>
                            <span>حساب جديد</span>
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories Nav -->
        <div class="navbar-categories">
            <div class="container">
                <ul class="category-nav">
                    <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') && !request('category') ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap me-1"></i> جميع المنتجات</a></li>
                    @php $navCategories = \App\Models\Category::active()->parents()->limit(8)->get(); @endphp
                    @foreach($navCategories as $cat)
                    <li><a href="{{ route('categories.show', $cat) }}">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#allCategoriesModal"><i class="bi bi-three-dots"></i> المزيد</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
        <div class="custom-toast success animate__animated animate__fadeInLeft">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <strong>تم بنجاح</strong>
                <p class="mb-0 small">{{ session('success') }}</p>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="custom-toast error animate__animated animate__fadeInLeft">
            <i class="bi bi-x-circle-fill"></i>
            <div>
                <strong>خطأ</strong>
                <p class="mb-0 small">{{ session('error') }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Main Content -->
    <main class="flex-grow-1">
        {{ $slot }}
    </main>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5><i class="bi bi-gear-wide-connected me-2 text-warning"></i> AutoParts Hub</h5>
                    <p class="text-white-50">متجرك الأول لقطع غيار السيارات الأصلية والبديلة. نوفر لك أفضل المنتجات بأسعار منافسة مع ضمان الجودة وسرعة التوصيل.</p>
                    <div class="social-links mt-4">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-snapchat"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>روابط سريعة</h5>
                    <ul>
                        <li><a href="/">الرئيسية</a></li>
                        <li><a href="{{ route('products.index') }}">المنتجات</a></li>
                        <li><a href="{{ route('stores.index') }}">المتاجر</a></li>
                        <li><a href="#">العروض</a></li>
                        <li><a href="#">من نحن</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>خدمة العملاء</h5>
                    <ul>
                        <li><a href="#">تواصل معنا</a></li>
                        <li><a href="#">الأسئلة الشائعة</a></li>
                        <li><a href="#">سياسة الإرجاع</a></li>
                        <li><a href="#">سياسة الخصوصية</a></li>
                        <li><a href="#">الشروط والأحكام</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5>تواصل معنا</h5>
                    <ul class="footer-contact">
                        <li><i class="bi bi-geo-alt-fill"></i> الرياض، المملكة العربية السعودية</li>
                        <li><i class="bi bi-telephone-fill"></i> 966500000000+</li>
                        <li><i class="bi bi-envelope-fill"></i> info@autoparts.sa</li>
                        <li><i class="bi bi-clock-fill"></i> السبت - الخميس: 9ص - 10م</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-2">© {{ date('Y') }} AutoParts Hub. جميع الحقوق محفوظة</p>
                <div class="payment-methods">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal">
                    <img src="https://www.mada.com.sa/themes/starter/images/logo.svg" alt="Mada" style="background: white; padding: 5px; border-radius: 5px;">
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </button>
    
    <!-- All Categories Modal -->
    <div class="modal fade" id="allCategoriesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-grid-3x3-gap me-2"></i> جميع التصنيفات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @php $allCategories = \App\Models\Category::active()->parents()->with('children')->get(); @endphp
                        @foreach($allCategories as $category)
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <a href="{{ route('categories.show', $category) }}" class="text-dark text-decoration-none">
                                            <i class="bi {{ $category->icon ?? 'bi-folder' }} me-2 text-primary"></i>
                                            {{ $category->name }}
                                        </a>
                                    </h6>
                                    @if($category->children->count() > 0)
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($category->children->take(5) as $child)
                                        <li class="mb-1">
                                            <a href="{{ route('categories.show', $child) }}" class="text-muted text-decoration-none">{{ $child->name }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Compare Bar -->
    <div class="compare-bar" id="compareBar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="compare-items" id="compareItems"></div>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-primary" id="compareBtn">
                        <i class="bi bi-arrow-left-right me-1"></i> مقارنة (<span id="compareCount">0</span>)
                    </a>
                    <button class="btn btn-outline-secondary" onclick="clearCompare()">
                        <i class="bi bi-x-lg"></i> مسح
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
        // Back to Top
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Toast Auto Hide
        setTimeout(() => {
            document.querySelectorAll('.custom-toast').forEach(toast => {
                toast.classList.add('animate__fadeOutLeft');
                setTimeout(() => toast.remove(), 500);
            });
        }, 5000);
        
        // Search Suggestions
        const searchInput = document.getElementById('searchInput');
        const searchSuggestions = document.getElementById('searchSuggestions');
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchSuggestions.classList.remove('show');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`/api/search-suggestions?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            searchSuggestions.innerHTML = data.map(item => `
                                <a href="/products/${item.slug}" class="search-suggestion-item">
                                    <img src="${item.image}" alt="">
                                    <div>
                                        <strong>${item.name}</strong>
                                        <small class="text-muted d-block">${item.price} ر.س</small>
                                    </div>
                                </a>
                            `).join('');
                            searchSuggestions.classList.add('show');
                        } else {
                            searchSuggestions.classList.remove('show');
                        }
                    })
                    .catch(() => searchSuggestions.classList.remove('show'));
            }, 300);
        });
        
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.classList.remove('show');
            }
        });
        
        // Compare Products
        let compareProducts = JSON.parse(localStorage.getItem('compareProducts') || '[]');
        updateCompareBar();
        
        function addToCompare(productId, name, image) {
            if (compareProducts.length >= 4) {
                showToast('error', 'يمكنك مقارنة 4 منتجات كحد أقصى');
                return;
            }
            
            if (compareProducts.find(p => p.id === productId)) {
                showToast('error', 'المنتج موجود بالفعل في المقارنة');
                return;
            }
            
            compareProducts.push({ id: productId, name, image });
            localStorage.setItem('compareProducts', JSON.stringify(compareProducts));
            updateCompareBar();
            showToast('success', 'تمت إضافة المنتج للمقارنة');
        }
        
        function removeFromCompare(productId) {
            compareProducts = compareProducts.filter(p => p.id !== productId);
            localStorage.setItem('compareProducts', JSON.stringify(compareProducts));
            updateCompareBar();
        }
        
        function clearCompare() {
            compareProducts = [];
            localStorage.setItem('compareProducts', JSON.stringify(compareProducts));
            updateCompareBar();
        }
        
        function updateCompareBar() {
            const compareBar = document.getElementById('compareBar');
            const compareItems = document.getElementById('compareItems');
            const compareCount = document.getElementById('compareCount');
            
            compareCount.textContent = compareProducts.length;
            
            if (compareProducts.length > 0) {
                compareBar.classList.add('show');
                compareItems.innerHTML = compareProducts.map(p => `
                    <div class="compare-item">
                        <img src="${p.image}" alt="${p.name}">
                        <span class="small">${p.name.substring(0, 20)}...</span>
                        <button class="btn btn-sm btn-link text-danger p-0" onclick="removeFromCompare(${p.id})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `).join('');
            } else {
                compareBar.classList.remove('show');
            }
        }
        
        function showToast(type, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `custom-toast ${type} animate__animated animate__fadeInLeft`;
            toast.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'x-circle-fill'}"></i>
                <div>
                    <strong>${type === 'success' ? 'تم بنجاح' : 'خطأ'}</strong>
                    <p class="mb-0 small">${message}</p>
                </div>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('animate__fadeOutLeft');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
        
        // Add to Cart Animation
        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'تمت إضافة المنتج للسلة');
                    // Update cart count
                    const cartBadge = document.querySelector('.nav-icon-btn .badge');
                    if (cartBadge) {
                        cartBadge.textContent = parseInt(cartBadge.textContent || 0) + 1;
                    }
                } else {
                    showToast('error', data.message || 'حدث خطأ');
                }
            })
            .catch(() => showToast('error', 'حدث خطأ في الاتصال'));
        }
        
        // Add to Wishlist
        function addToWishlist(productId) {
            fetch(`/wishlist/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'تمت إضافة المنتج للمفضلة');
                } else {
                    showToast('error', data.message || 'حدث خطأ');
                }
            })
            .catch(() => showToast('error', 'حدث خطأ في الاتصال'));
        }
    </script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
