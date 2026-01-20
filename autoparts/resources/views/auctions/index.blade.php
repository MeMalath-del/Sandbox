@extends('layouts.app')

@section('title', 'المزادات')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3"><i class="bi bi-hammer text-danger"></i> المزادات</h1>
        <p class="text-muted lead">زايد على قطع الغيار واحصل على أفضل الأسعار</p>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('auctions.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">الفئة</label>
                    <select name="category" class="form-select">
                        <option value="">جميع الفئات</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="ending_soon" value="1" class="form-check-input" id="endingSoon" {{ request('ending_soon') ? 'checked' : '' }}>
                        <label class="form-check-label" for="endingSoon">ينتهي قريباً (خلال 24 ساعة)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">بحث</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Auctions Grid -->
    @if($auctions->count() > 0)
    <div class="row g-4">
        @foreach($auctions as $auction)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                <div class="position-relative">
                    <img src="{{ $auction->product->primaryImage?->url ?? '/images/placeholder.jpg' }}" class="card-img-top" alt="{{ $auction->product->name }}" style="height: 200px; object-fit: cover;">
                    
                    <!-- Time Remaining -->
                    <div class="position-absolute top-0 start-0 m-2">
                        @php
                            $timeRemaining = now()->diff($auction->ends_at);
                            $hours = $timeRemaining->days * 24 + $timeRemaining->h;
                        @endphp
                        <span class="badge {{ $hours < 6 ? 'bg-danger' : ($hours < 24 ? 'bg-warning' : 'bg-primary') }} fs-6">
                            <i class="bi bi-clock me-1"></i>
                            @if($hours >= 24)
                                {{ $timeRemaining->days }} يوم
                            @else
                                {{ sprintf('%02d:%02d:%02d', $hours, $timeRemaining->i, $timeRemaining->s) }}
                            @endif
                        </span>
                    </div>

                    <!-- Bids Count -->
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark">
                            <i class="bi bi-people me-1"></i>{{ $auction->bids->count() }} مزايدة
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('auctions.show', $auction) }}" class="text-dark text-decoration-none">
                            {{ Str::limit($auction->product->name, 40) }}
                        </a>
                    </h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <small class="text-muted d-block">السعر الحالي</small>
                            <span class="fs-4 fw-bold text-primary">{{ number_format($auction->current_price, 2) }} ر.س</span>
                        </div>
                        @if($auction->buy_now_price)
                        <div class="text-end">
                            <small class="text-muted d-block">الشراء الآن</small>
                            <span class="fs-5 text-success">{{ number_format($auction->buy_now_price, 2) }} ر.س</span>
                        </div>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary">
                            <i class="bi bi-hammer me-2"></i>زايد الآن
                        </a>
                        @if($auction->buy_now_price)
                        <form action="{{ route('auctions.buy-now', $auction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="bi bi-cart-check me-2"></i>اشترِ الآن
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $auctions->links() }}
    @else
    <div class="text-center py-5">
        <i class="bi bi-hammer fs-1 text-muted"></i>
        <p class="text-muted mt-3">لا توجد مزادات نشطة حالياً</p>
    </div>
    @endif
</div>
@endsection
