@extends('layouts.app')

@section('title', 'تخفيضات فلاش')

@section('content')
<div class="container py-5">
    <!-- Active Flash Sale -->
    @if($activeFlashSale)
    <div class="card border-0 shadow-lg mb-5 overflow-hidden" style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);">
        <div class="card-body text-white text-center py-5">
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-lightning-charge-fill"></i> تخفيضات فلاش
            </h1>
            <p class="lead mb-4">{{ $activeFlashSale->name }}</p>
            
            <!-- Countdown Timer -->
            <div class="d-flex justify-content-center gap-4 mb-4" id="flashCountdown" data-ends="{{ $activeFlashSale->ends_at->toIso8601String() }}">
                <div class="text-center">
                    <div class="display-4 fw-bold" id="hours">00</div>
                    <small>ساعة</small>
                </div>
                <div class="display-4">:</div>
                <div class="text-center">
                    <div class="display-4 fw-bold" id="minutes">00</div>
                    <small>دقيقة</small>
                </div>
                <div class="display-4">:</div>
                <div class="text-center">
                    <div class="display-4 fw-bold" id="seconds">00</div>
                    <small>ثانية</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Sale Products -->
    <div class="row g-4">
        @foreach($activeFlashSale->items as $item)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                <div class="position-relative">
                    <img src="{{ $item->product->primaryImage?->url ?? '/images/placeholder.jpg' }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 200px; object-fit: cover;">
                    
                    <!-- Discount Badge -->
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-danger fs-5">-{{ $item->discount_percentage }}%</span>
                    </div>

                    <!-- Stock Progress -->
                    <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-75">
                        <div class="progress" style="height: 8px;">
                            @php $soldPercent = ($item->sold_quantity / $item->quantity_limit) * 100; @endphp
                            <div class="progress-bar bg-danger" style="width: {{ $soldPercent }}%"></div>
                        </div>
                        <small class="text-white">تبقى {{ $item->remaining_quantity }} فقط</small>
                    </div>
                </div>

                <div class="card-body">
                    <h6 class="card-title">{{ Str::limit($item->product->name, 40) }}</h6>
                    
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fs-4 fw-bold text-danger">{{ number_format($item->sale_price, 2) }} ر.س</span>
                        <span class="text-muted text-decoration-line-through">{{ number_format($item->product->price, 2) }} ر.س</span>
                    </div>

                    <form action="{{ route('flash-sales.purchase', $item) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100" {{ $item->remaining_quantity <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-bag-plus me-2"></i>
                            {{ $item->remaining_quantity <= 0 ? 'نفدت الكمية' : 'اشترِ الآن' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- No Active Flash Sale -->
    <div class="text-center py-5">
        <i class="bi bi-lightning-charge fs-1 text-muted"></i>
        <h3 class="mt-3">لا توجد تخفيضات فلاش نشطة حالياً</h3>
        <p class="text-muted">تابعنا لتكون أول من يعلم عن التخفيضات القادمة</p>
    </div>
    @endif

    <!-- Upcoming Flash Sales -->
    @if($upcomingFlashSales->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4"><i class="bi bi-calendar-event me-2"></i>تخفيضات قادمة</h3>
        <div class="row g-4">
            @foreach($upcomingFlashSales as $upcoming)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5>{{ $upcoming->name }}</h5>
                        <p class="text-muted mb-3">{{ $upcoming->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-clock me-1"></i>
                                {{ $upcoming->starts_at->format('Y/m/d H:i') }}
                            </span>
                            <form action="{{ route('flash-sales.notify', $upcoming) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-bell me-1"></i>نبهني
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
const countdown = document.getElementById('flashCountdown');
if (countdown) {
    const endTime = new Date(countdown.dataset.ends).getTime();
    
    const timer = setInterval(() => {
        const now = new Date().getTime();
        const diff = endTime - now;
        
        if (diff < 0) {
            clearInterval(timer);
            location.reload();
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }, 1000);
}
</script>
@endpush
@endsection
