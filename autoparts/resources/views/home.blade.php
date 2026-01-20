<x-app-layout>
    <x-slot name="title">الرئيسية</x-slot>
    
    <!-- Hero Slider -->
    <section class="hero-section">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide hero-slide" style="background: linear-gradient(135deg, #1a56db 0%, #1e40af 100%);">
                    <div class="container">
                        <div class="row align-items-center min-vh-50">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2">عروض حصرية</span>
                                <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInUp">قطع غيار أصلية بأسعار منافسة</h1>
                                <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">اكتشف مجموعتنا الواسعة من قطع الغيار الأصلية والبديلة لجميع أنواع السيارات</p>
                                <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-4">
                                        <i class="bi bi-cart me-2"></i> تسوق الآن
                                    </a>
                                    <a href="{{ route('products.index', ['sale' => 1]) }}" class="btn btn-outline-light btn-lg px-4">
                                        <i class="bi bi-percent me-2"></i> العروض
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 text-center d-none d-lg-block">
                                <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600" alt="Auto Parts" class="img-fluid hero-img animate__animated animate__fadeInRight">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide hero-slide" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                    <div class="container">
                        <div class="row align-items-center min-vh-50">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-dark mb-3 px-3 py-2">جديد</span>
                                <h1 class="display-4 fw-bold mb-4">خصم يصل إلى 50%</h1>
                                <p class="lead mb-4">على جميع قطع الفرامل والإضاءة - العرض محدود!</p>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-lg px-4">
                                    <i class="bi bi-arrow-left me-2"></i> اكتشف العروض
                                </a>
                            </div>
                            <div class="col-lg-6 text-center d-none d-lg-block">
                                <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600" alt="Car Parts" class="img-fluid hero-img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide hero-slide" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="container">
                        <div class="row align-items-center min-vh-50">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2">شحن مجاني</span>
                                <h1 class="display-4 fw-bold mb-4">توصيل مجاني للطلبات فوق 200 ر.س</h1>
                                <p class="lead mb-4">استمتع بالتوصيل المجاني لجميع مناطق المملكة</p>
                                <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-4">
                                    <i class="bi bi-truck me-2"></i> تسوق الآن
                                </a>
                            </div>
                            <div class="col-lg-6 text-center d-none d-lg-block">
                                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600" alt="Delivery" class="img-fluid hero-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    
    <!-- Features -->
    <section class="features-section py-4 bg-white border-bottom">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="feature-item d-flex align-items-center gap-3">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">شحن سريع</h6>
                            <small class="text-muted">توصيل لجميع المناطق</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-item d-flex align-items-center gap-3">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">ضمان الجودة</h6>
                            <small class="text-muted">منتجات أصلية 100%</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-item d-flex align-items-center gap-3">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">استرجاع سهل</h6>
                            <small class="text-muted">خلال 14 يوم</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="feature-item d-flex align-items-center gap-3">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">دعم فني</h6>
                            <small class="text-muted">24/7 متواجدون</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Flash Deals -->
    @if($saleProducts->count() > 0)
    <section class="flash-deals-section py-5">
        <div class="container">
            <div class="flash-deals-header d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="flash-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 fw-bold">عروض اليوم</h2>
                        <small class="text-muted">ينتهي العرض خلال:</small>
                    </div>
                    <div class="countdown d-flex gap-2" id="countdown">
                        <div class="countdown-item">
                            <span id="hours">12</span>
                            <small>ساعة</small>
                        </div>
                        <div class="countdown-item">
                            <span id="minutes">45</span>
                            <small>دقيقة</small>
                        </div>
                        <div class="countdown-item">
                            <span id="seconds">30</span>
                            <small>ثانية</small>
                        </div>
                    </div>
                </div>
                <a href="{{ route('products.index', ['sale' => 1]) }}" class="btn btn-outline-danger">
                    عرض الكل <i class="bi bi-arrow-left ms-1"></i>
                </a>
            </div>
            
            <div class="swiper deals-swiper">
                <div class="swiper-wrapper">
                    @foreach($saleProducts as $product)
                    <div class="swiper-slide">
                        @include('components.product-card', ['product' => $product])
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    @endif
    
    <!-- Categories -->
    <section class="categories-section py-5 bg-white">
        <div class="container">
            <div class="section-header">
                <h2><i class="bi bi-grid-3x3-gap"></i> تصفح حسب التصنيف</h2>
                <a href="{{ route('products.index') }}" class="view-all">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @foreach($categories as $category)
                <div class="col-lg-2 col-md-3 col-4">
                    <a href="{{ route('categories.show', $category) }}" class="category-card text-center d-block text-decoration-none">
                        <div class="category-icon">
                            <i class="bi {{ $category->icon ?? 'bi-box' }}"></i>
                        </div>
                        <h6 class="mt-3 mb-0">{{ $category->name }}</h6>
                        <small class="text-muted">{{ $category->products_count }} منتج</small>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Best Sellers -->
    <section class="products-section py-5">
        <div class="container">
            <div class="section-header">
                <h2><i class="bi bi-trophy"></i> الأكثر مبيعاً</h2>
                <a href="{{ route('products.index', ['sort' => 'bestseller']) }}" class="view-all">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @foreach($bestsellerProducts as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    @include('components.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Banner -->
    <section class="banner-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="promo-banner promo-banner-1">
                        <div class="promo-content">
                            <span class="badge bg-danger mb-2">خصم 30%</span>
                            <h3>قطع غيار المحرك</h3>
                            <p>أفضل الماركات العالمية</p>
                            <a href="{{ route('products.index') }}" class="btn btn-dark">تسوق الآن</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="promo-banner promo-banner-2">
                        <div class="promo-content">
                            <span class="badge bg-warning text-dark mb-2">جديد</span>
                            <h3>إكسسوارات السيارات</h3>
                            <p>تشكيلة واسعة ومتنوعة</p>
                            <a href="{{ route('products.index') }}" class="btn btn-warning">اكتشف المزيد</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- New Arrivals -->
    <section class="products-section py-5 bg-white">
        <div class="container">
            <div class="section-header">
                <h2><i class="bi bi-stars"></i> وصل حديثاً</h2>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="view-all">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @foreach($newProducts as $product)
                <div class="col-lg-3 col-md-4 col-6">
                    @include('components.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Brands -->
    <section class="brands-section py-5">
        <div class="container">
            <div class="section-header">
                <h2><i class="bi bi-award"></i> الماركات المميزة</h2>
                <a href="{{ route('products.index') }}" class="view-all">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="swiper brands-swiper">
                <div class="swiper-wrapper">
                    @foreach($brands as $brand)
                    <div class="swiper-slide">
                        <a href="{{ route('brands.show', $brand) }}" class="brand-card d-flex align-items-center justify-content-center">
                            @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid">
                            @else
                            <span class="fw-bold text-muted">{{ $brand->name }}</span>
                            @endif
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Stores -->
    @if($stores->count() > 0)
    <section class="stores-section py-5 bg-white">
        <div class="container">
            <div class="section-header">
                <h2><i class="bi bi-shop"></i> متاجر مميزة</h2>
                <a href="{{ route('stores.index') }}" class="view-all">
                    عرض الكل <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @foreach($stores as $store)
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="{{ route('stores.show', $store) }}" class="store-card d-block text-decoration-none">
                        <div class="store-cover" style="background-image: url('{{ $store->cover_image ? asset('storage/' . $store->cover_image) : 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400' }}')"></div>
                        <div class="store-info text-center">
                            <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="store-logo">
                            <h6 class="mt-3 mb-1">
                                {{ $store->name }}
                                @if($store->is_verified)
                                <i class="bi bi-patch-check-fill text-primary"></i>
                                @endif
                            </h6>
                            <div class="store-stats text-muted small">
                                <span><i class="bi bi-star-fill text-warning"></i> {{ number_format($store->rating, 1) }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $store->products_count }} منتج</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    <!-- Newsletter -->
    <section class="newsletter-section py-5">
        <div class="container">
            <div class="newsletter-box">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h3 class="text-white mb-2"><i class="bi bi-envelope-paper me-2"></i> اشترك في النشرة البريدية</h3>
                        <p class="text-white-50 mb-0">احصل على أحدث العروض والخصومات مباشرة على بريدك الإلكتروني</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form d-flex gap-2 mt-3 mt-lg-0">
                            <input type="email" class="form-control form-control-lg" placeholder="أدخل بريدك الإلكتروني">
                            <button type="submit" class="btn btn-warning btn-lg px-4">اشتراك</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <style>
        .hero-section {
            margin-bottom: 0;
        }
        
        .hero-slide {
            padding: 60px 0;
        }
        
        .min-vh-50 {
            min-height: 50vh;
        }
        
        .hero-img {
            max-height: 400px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .hero-swiper .swiper-button-next,
        .hero-swiper .swiper-button-prev {
            color: white;
            background: rgba(255,255,255,0.2);
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        
        .hero-swiper .swiper-button-next:after,
        .hero-swiper .swiper-button-prev:after {
            font-size: 1.2rem;
        }
        
        .hero-swiper .swiper-pagination-bullet {
            background: white;
            opacity: 0.5;
        }
        
        .hero-swiper .swiper-pagination-bullet-active {
            opacity: 1;
            width: 30px;
            border-radius: 10px;
        }
        
        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a56db 0%, #1e40af 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }
        
        .flash-deals-section {
            background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
        }
        
        .flash-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .countdown {
            margin-right: 20px;
        }
        
        .countdown-item {
            background: #ef4444;
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            text-align: center;
            min-width: 60px;
        }
        
        .countdown-item span {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }
        
        .countdown-item small {
            font-size: 0.7rem;
        }
        
        .category-card {
            padding: 25px 15px;
            border-radius: 15px;
            transition: all 0.3s;
            background: #f8fafc;
        }
        
        .category-card:hover {
            background: linear-gradient(135deg, #1a56db 0%, #1e40af 100%);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(26, 86, 219, 0.2);
        }
        
        .category-card:hover h6,
        .category-card:hover small {
            color: white !important;
        }
        
        .category-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 1.8rem;
            color: #1a56db;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .category-card:hover .category-icon {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .promo-banner {
            border-radius: 20px;
            padding: 40px;
            min-height: 250px;
            display: flex;
            align-items: center;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        
        .promo-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        
        .promo-banner-1 {
            background-image: url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800');
        }
        
        .promo-banner-2 {
            background-image: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800');
        }
        
        .promo-content {
            position: relative;
            z-index: 1;
            color: white;
        }
        
        .promo-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .brand-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            height: 100px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .brand-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
        
        .brand-card img {
            max-height: 50px;
            max-width: 120px;
            object-fit: contain;
            filter: grayscale(100%);
            transition: filter 0.3s;
        }
        
        .brand-card:hover img {
            filter: grayscale(0%);
        }
        
        .store-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .store-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        
        .store-cover {
            height: 100px;
            background-size: cover;
            background-position: center;
        }
        
        .store-info {
            padding: 20px;
            margin-top: -40px;
        }
        
        .store-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .newsletter-section {
            background: #1f2937;
        }
        
        .newsletter-box {
            background: linear-gradient(135deg, #1a56db 0%, #1e40af 100%);
            border-radius: 20px;
            padding: 40px;
        }
        
        .newsletter-form input {
            border-radius: 10px;
            border: none;
        }
        
        .deals-swiper,
        .brands-swiper {
            padding: 10px 0 40px;
        }
    </style>
    
    @push('scripts')
    <script>
        // Hero Swiper
        new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.hero-swiper .swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.hero-swiper .swiper-button-next',
                prevEl: '.hero-swiper .swiper-button-prev',
            },
        });
        
        // Deals Swiper
        new Swiper('.deals-swiper', {
            slidesPerView: 2,
            spaceBetween: 20,
            pagination: {
                el: '.deals-swiper .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 3 },
                992: { slidesPerView: 4 },
                1200: { slidesPerView: 5 },
            },
        });
        
        // Brands Swiper
        new Swiper('.brands-swiper', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: { slidesPerView: 4 },
                992: { slidesPerView: 6 },
                1200: { slidesPerView: 8 },
            },
        });
        
        // Countdown Timer
        function updateCountdown() {
            const now = new Date();
            const endOfDay = new Date(now);
            endOfDay.setHours(23, 59, 59, 999);
            const diff = endOfDay - now;
            
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
    @endpush
</x-app-layout>
