@extends('layouts.app')

@section('title', 'حزم المنتجات')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3"><i class="bi bi-boxes text-primary"></i> حزم المنتجات</h1>
        <p class="text-muted lead">وفّر أكثر عند شراء مجموعات كاملة من قطع الغيار</p>
    </div>

    <!-- Bundles Grid -->
    @if($bundles->count() > 0)
    <div class="row g-4">
        @foreach($bundles as $bundle)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                @if($bundle->image)
                <img src="{{ $bundle->image_url }}" class="card-img-top" alt="{{ $bundle->name }}" style="height: 200px; object-fit: cover;">
                @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <div class="row g-1" style="width: 80%;">
                        @foreach($bundle->items->take(4) as $item)
                        <div class="col-6">
                            <img src="{{ $item->product->primaryImage?->url ?? '/images/placeholder.jpg' }}" class="img-fluid rounded" style="height: 80px; object-fit: cover; width: 100%;">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Discount Badge -->
                <div class="position-absolute top-0 start-0 m-2">
                    <span class="badge bg-success fs-6">وفر {{ number_format($bundle->discount_percentage) }}%</span>
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $bundle->name }}</h5>
                    <p class="text-muted small">{{ Str::limit($bundle->description, 60) }}</p>

                    <!-- Products List -->
                    <ul class="list-unstyled small mb-3">
                        @foreach($bundle->items->take(3) as $item)
                        <li>
                            <i class="bi bi-check-circle text-success me-1"></i>
                            {{ $item->product->name }} (x{{ $item->quantity }})
                        </li>
                        @endforeach
                        @if($bundle->items->count() > 3)
                        <li class="text-muted">+ {{ $bundle->items->count() - 3 }} منتجات أخرى</li>
                        @endif
                    </ul>

                    <!-- Price -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fs-4 fw-bold text-primary">{{ number_format($bundle->price, 2) }} ر.س</span>
                        <span class="text-muted text-decoration-line-through">{{ number_format($bundle->original_price, 2) }} ر.س</span>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('bundles.show', $bundle) }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye me-2"></i>عرض التفاصيل
                        </a>
                        <form action="{{ route('bundles.addToCart', $bundle) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-cart-plus me-2"></i>أضف للسلة
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Store -->
                <div class="card-footer bg-white text-muted small">
                    <i class="bi bi-shop me-1"></i>{{ $bundle->store->name }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $bundles->links() }}
    @else
    <div class="text-center py-5">
        <i class="bi bi-boxes fs-1 text-muted"></i>
        <p class="text-muted mt-3">لا توجد حزم متاحة حالياً</p>
    </div>
    @endif
</div>
@endsection
