@extends('layouts.app')

@section('title', 'كوبونات الخصم')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3"><i class="bi bi-ticket-perforated text-success"></i> كوبونات الخصم</h1>
        <p class="text-muted lead">استخدم أكواد الخصم للتوفير على مشترياتك</p>
    </div>

    @if($coupons->count() > 0)
    <div class="row g-4">
        @foreach($coupons as $coupon)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative p-4">
                    <!-- Discount Badge -->
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        <span class="badge bg-danger fs-5">
                            @if($coupon->type === 'fixed')
                            {{ number_format($coupon->discount_value) }} ر.س
                            @else
                            {{ $coupon->discount_value }}%
                            @endif
                        </span>
                    </div>

                    <!-- Store Name -->
                    @if($coupon->store)
                    <small class="text-muted d-block mb-2">{{ $coupon->store->name }}</small>
                    @else
                    <small class="text-primary d-block mb-2">كوبون عام</small>
                    @endif

                    <!-- Code -->
                    <div class="bg-light rounded p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <code class="fs-5 text-dark" id="code{{ $coupon->id }}">{{ $coupon->code }}</code>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyCode('{{ $coupon->code }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Details -->
                    <ul class="list-unstyled small text-muted mb-0">
                        @if($coupon->min_order_amount)
                        <li><i class="bi bi-info-circle me-1"></i>الحد الأدنى للطلب: {{ number_format($coupon->min_order_amount) }} ر.س</li>
                        @endif
                        @if($coupon->max_discount && $coupon->type === 'percentage')
                        <li><i class="bi bi-info-circle me-1"></i>أقصى خصم: {{ number_format($coupon->max_discount) }} ر.س</li>
                        @endif
                        @if($coupon->expires_at)
                        <li><i class="bi bi-clock me-1"></i>صالح حتى: {{ $coupon->expires_at->format('Y/m/d') }}</li>
                        @endif
                        @if($coupon->usage_limit)
                        <li><i class="bi bi-people me-1"></i>متبقي: {{ $coupon->usage_limit - $coupon->times_used }} استخدام</li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer bg-white border-0 p-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary w-100">
                        تسوق الآن
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $coupons->links() }}
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-ticket-perforated fs-1 text-muted"></i>
            <p class="text-muted mt-3">لا توجد كوبونات متاحة حالياً</p>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function copyCode(code) {
    navigator.clipboard.writeText(code);
    alert('تم نسخ الكود: ' + code);
}
</script>
@endpush
@endsection
