<x-app-layout>
    <x-slot name="title">الرئيسية</x-slot>
    
    <!-- Hero Slider -->
    <section class="mb-5">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @for($i = 0; $i < max(1, count($banners ?? [])); $i++)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" @if($i === 0) class="active" @endif></button>
                @endfor
            </div>
            <div class="carousel-inner">
                @forelse($banners ?? [] as $index => $banner)
                <div class="carousel-item @if($index === 0) active @endif">
                    <img src="{{ $banner->image_url }}" class="d-block w-100" alt="{{ $banner->title }}" style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption">
                        <h2>{{ $banner->title }}</h2>
                        <p>{{ $banner->subtitle }}</p>
                        @if($banner->link_url)
                        <a href="{{ $banner->link_url }}" class="btn btn-primary btn-lg">تسوق الآن</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="carousel-item active" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); height: 400px;">
                    <div class="d-flex align-items-center justify-content-center h-100 text-white text-center">
                        <div>
                            <h1 class="display-4 fw-bold mb-3">مرحباً بك في AutoParts Hub</h1>
                            <p class="lead mb-4">أكبر منصة لقطع غيار السيارات في المملكة</p>
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">تصفح المنتجات</a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>
    
    <!-- Features -->
    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-truck fs-2 text-primary me-3"></i>
                        <div class="text-start">
                            <h6 class="mb-0">توصيل سريع</h6>
                            <small class="text-muted">لجميع المناطق</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-check fs-2 text-primary me-3"></i>
                        <div class="text-start">
                            <h6 class="mb-0">ضمان الجودة</h6>
                            <small class="text-muted">منتجات أصلية</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-repeat fs-2 text-primary me-3"></i>
                        <div class="text-start">
                            <h6 class="mb-0">إرجاع سهل</h6>
                            <small class="text-muted">خلال 14 يوم</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-headset fs-2 text-primary me-3"></i>
                        <div class="text-start">
                            <h6 class="mb-0">دعم 24/7</h6>
                            <small class="text-muted">خدمة عملاء متميزة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container py-5">
        <!-- Categories -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-grid text-primary me-2"></i>
                    تصفح حسب التصنيف
                </h3>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">عرض الكل</a>
            </div>
            <div class="row g-4">
                @forelse($featuredCategories ?? [] as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <div class="card text-center p-4 h-100 product-card">
                            <div class="mb-3">
                                @if($category->icon)
                                <i class="bi {{ $category->icon }} fs-1 text-primary"></i>
                                @else
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid" style="height: 80px; object-fit: contain;">
                                @endif
                            </div>
                            <h6 class="mb-1 text-dark">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} منتج</small>
                        </div>
                    </a>
                </div>
                @empty
                @foreach(['المحرك', 'الفرامل', 'الكهرباء', 'التعليق', 'الإطارات', 'الزيوت', 'الفلاتر', 'الإضاءة'] as $name)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card text-center p-4 h-100 product-card">
                        <div class="mb-3">
                            <i class="bi bi-gear fs-1 text-primary"></i>
                        </div>
                        <h6 class="mb-1">{{ $name }}</h6>
                        <small class="text-muted">0 منتج</small>
                    </div>
                </div>
                @endforeach
                @endforelse
            </div>
        </section>
        
        <!-- Brands -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-stars text-primary me-2"></i>
                    الماركات الشهيرة
                </h3>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">عرض الكل</a>
            </div>
            <div class="card p-4">
                <div class="row g-4 align-items-center justify-content-center">
                    @forelse($featuredBrands ?? [] as $brand)
                    <div class="col-4 col-md-2 text-center">
                        <a href="{{ route('brands.show', $brand) }}">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 50px; filter: grayscale(100%); transition: all 0.3s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                        </a>
                    </div>
                    @empty
                    @foreach(['Toyota', 'Hyundai', 'Nissan', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Honda', 'Kia', 'Mazda', 'GMC', 'Lexus'] as $brand)
                    <div class="col-4 col-md-2 text-center">
                        <span class="badge bg-light text-dark fs-6 p-3">{{ $brand }}</span>
                    </div>
                    @endforeach
                    @endforelse
                </div>
            </div>
        </section>
        
        <!-- Featured Products -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    منتجات مميزة
                </h3>
                <a href="{{ route('products.index') }}?featured=1" class="btn btn-outline-primary">عرض الكل</a>
            </div>
            <div class="row g-4">
                @forelse($featuredProducts ?? [] as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('components.product-card', ['product' => $product])
                </div>
                @empty
                @for($i = 0; $i < 4; $i++)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image fs-1 text-muted"></i>
                            </div>
                            <span class="badge bg-warning position-absolute top-0 start-0 m-2">مميز</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-1">اسم المتجر</p>
                            <h6 class="card-title mb-2">اسم المنتج</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="text-muted small me-2">(0)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-primary">0.00 ر.س</span>
                                </div>
                                <button class="btn btn-primary btn-sm">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>
        
        <!-- Sale Products -->
        <section class="mb-5">
            <div class="card bg-danger bg-gradient text-white p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-2">
                            <i class="bi bi-lightning-fill me-2"></i>
                            عروض حصرية
                        </h3>
                        <p class="mb-0 opacity-75">خصومات تصل إلى 50% على قطع غيار مختارة</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('products.index') }}?sale=1" class="btn btn-light">
                            تسوق العروض <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @forelse($onSaleProducts ?? [] as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('components.product-card', ['product' => $product])
                </div>
                @empty
                @for($i = 0; $i < 4; $i++)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-image fs-1 text-muted"></i>
                            </div>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-25%</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-1">اسم المتجر</p>
                            <h6 class="card-title mb-2">اسم المنتج</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted text-decoration-line-through small">100 ر.س</span>
                                    <span class="fw-bold text-danger d-block">75 ر.س</span>
                                </div>
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>
        
        <!-- Top Stores -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-shop text-primary me-2"></i>
                    متاجر مميزة
                </h3>
                <a href="{{ route('stores.index') }}" class="btn btn-outline-primary">عرض الكل</a>
            </div>
            <div class="row g-4">
                @forelse($topStores ?? [] as $store)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('stores.show', $store) }}" class="text-decoration-none">
                        <div class="card h-100 product-card">
                            <div class="card-body text-center p-4">
                                <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <h5 class="mb-1 text-dark">{{ $store->name }}</h5>
                                <p class="text-muted small mb-2">{{ $store->short_description ?? 'متجر قطع غيار' }}</p>
                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    <span class="text-warning">
                                        <i class="bi bi-star-fill"></i> {{ number_format($store->rating, 1) }}
                                    </span>
                                    <span class="text-muted">{{ $store->total_products }} منتج</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                @for($i = 0; $i < 3; $i++)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 product-card">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-shop fs-3 text-muted"></i>
                            </div>
                            <h5 class="mb-1">اسم المتجر</h5>
                            <p class="text-muted small mb-2">متجر قطع غيار</p>
                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <span class="text-warning"><i class="bi bi-star-fill"></i> 4.5</span>
                                <span class="text-muted">0 منتج</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="mb-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card bg-primary bg-gradient text-white h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">
                                <i class="bi bi-shop me-2"></i>
                                هل لديك متجر قطع غيار؟
                            </h4>
                            <p class="mb-4">انضم إلى منصتنا وابدأ ببيع منتجاتك لآلاف العملاء</p>
                            <a href="{{ route('register') }}" class="btn btn-light">سجل كمتجر</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success bg-gradient text-white h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">
                                <i class="bi bi-truck me-2"></i>
                                هل تريد العمل كموصل؟
                            </h4>
                            <p class="mb-4">انضم لفريق الموصلين واكسب دخل إضافي بمرونة</p>
                            <a href="{{ route('register') }}" class="btn btn-light">سجل كموصل</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
