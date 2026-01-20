@extends('layouts.app')

@section('title', 'من نحن')

@section('content')
<!-- Hero -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">قطع غيار السيارات</h1>
                <p class="lead mb-0">وجهتك الأولى لجميع قطع غيار السيارات في المملكة العربية السعودية</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Stats -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <i class="bi bi-box-seam fs-1 text-primary mb-3"></i>
                    <h2 class="display-5 fw-bold text-primary">{{ number_format($stats['products']) }}+</h2>
                    <p class="text-muted mb-0">منتج</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <i class="bi bi-shop fs-1 text-success mb-3"></i>
                    <h2 class="display-5 fw-bold text-success">{{ number_format($stats['stores']) }}+</h2>
                    <p class="text-muted mb-0">متجر</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <i class="bi bi-people fs-1 text-warning mb-3"></i>
                    <h2 class="display-5 fw-bold text-warning">{{ number_format($stats['customers']) }}+</h2>
                    <p class="text-muted mb-0">عميل</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-4">
                    <i class="bi bi-bag-check fs-1 text-danger mb-3"></i>
                    <h2 class="display-5 fw-bold text-danger">{{ number_format($stats['orders']) }}+</h2>
                    <p class="text-muted mb-0">طلب مكتمل</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About Content -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h2 class="h3 mb-4">قصتنا</h2>
            <p class="text-muted">
                بدأت رحلتنا في عام 2020 برؤية واضحة: توفير قطع غيار السيارات الأصلية بأسعار تنافسية وتجربة تسوق سهلة وموثوقة.
            </p>
            <p class="text-muted">
                اليوم، نفخر بكوننا أكبر منصة إلكترونية متخصصة في قطع غيار السيارات في المملكة، حيث نربط بين مئات الموردين الموثوقين وآلاف العملاء.
            </p>
            <p class="text-muted mb-0">
                نسعى دائماً لتقديم أفضل تجربة لعملائنا من خلال تشكيلة واسعة من المنتجات، أسعار تنافسية، وخدمة توصيل سريعة وموثوقة.
            </p>
        </div>
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600" class="img-fluid rounded-4 shadow" alt="Auto Parts">
        </div>
    </div>

    <!-- Values -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="h3 mb-4 text-center">قيمنا</h2>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-2 text-primary"></i>
                    </div>
                    <h5>الجودة والموثوقية</h5>
                    <p class="text-muted mb-0">نضمن لك قطع غيار أصلية من مصادر موثوقة ومعتمدة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-currency-dollar fs-2 text-success"></i>
                    </div>
                    <h5>أسعار تنافسية</h5>
                    <p class="text-muted mb-0">أفضل الأسعار في السوق مع عروض وخصومات مستمرة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-headset fs-2 text-warning"></i>
                    </div>
                    <h5>دعم متواصل</h5>
                    <p class="text-muted mb-0">فريق دعم متخصص جاهز لمساعدتك على مدار الساعة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="bg-light rounded-4 p-4 p-lg-5 mb-5">
        <h2 class="h3 mb-4 text-center">لماذا تختارنا؟</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div>
                        <h6>شحن سريع</h6>
                        <p class="text-muted small mb-0">توصيل خلال 24-48 ساعة لجميع مناطق المملكة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div>
                        <h6>ضمان الجودة</h6>
                        <p class="text-muted small mb-0">ضمان على جميع المنتجات مع سياسة إرجاع مرنة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div>
                        <h6>دفع آمن</h6>
                        <p class="text-muted small mb-0">طرق دفع متعددة وآمنة مع تشفير كامل للبيانات</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div>
                        <h6>أسعار تنافسية</h6>
                        <p class="text-muted small mb-0">أفضل الأسعار مع ضمان مطابقة السعر</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center">
        <h3 class="mb-4">هل أنت مستعد للبدء؟</h3>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">تصفح المنتجات</a>
            <a href="{{ route('pages.contact') }}" class="btn btn-outline-primary btn-lg">تواصل معنا</a>
        </div>
    </div>
</div>
@endsection
