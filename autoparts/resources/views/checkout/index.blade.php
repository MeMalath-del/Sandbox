<x-app-layout>
    <x-slot name="title">إتمام الشراء</x-slot>
    
    <div class="container py-5">
        <h2 class="fw-bold mb-4">إتمام الشراء</h2>
        
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-geo-alt me-2"></i> عنوان التوصيل
                        </div>
                        <div class="card-body">
                            @if($addresses && $addresses->count() > 0)
                            <div class="row g-3">
                                @foreach($addresses as $address)
                                <div class="col-md-6">
                                    <div class="form-check card h-100">
                                        <div class="card-body">
                                            <input class="form-check-input" type="radio" name="address_id" value="{{ $address->id }}" id="address{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="address{{ $address->id }}">
                                                <span class="fw-bold">{{ $address->label }}</span>
                                                @if($address->is_default)
                                                <span class="badge bg-primary float-end">الافتراضي</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    {{ $address->address_line_1 }}<br>
                                                    {{ $address->city }}, {{ $address->region }}<br>
                                                    {{ $address->phone }}
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="{{ route('profile.addresses') }}" class="btn btn-link mt-3">
                                <i class="bi bi-plus-circle me-1"></i> إضافة عنوان جديد
                            </a>
                            @else
                            <p class="text-muted mb-3">لا توجد عناوين مسجلة</p>
                            <a href="{{ route('profile.addresses') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> إضافة عنوان
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-credit-card me-2"></i> طريقة الدفع
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="cod" id="codPayment" checked>
                                <label class="form-check-label" for="codPayment">
                                    <i class="bi bi-cash-coin me-2 text-success"></i>
                                    الدفع عند الاستلام
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="wallet" id="walletPayment">
                                <label class="form-check-label" for="walletPayment">
                                    <i class="bi bi-wallet2 me-2 text-primary"></i>
                                    المحفظة (الرصيد: {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }} ر.س)
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" value="card" id="cardPayment">
                                <label class="form-check-label" for="cardPayment">
                                    <i class="bi bi-credit-card me-2 text-info"></i>
                                    بطاقة ائتمان/مدى
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-chat-text me-2"></i> ملاحظات الطلب (اختياري)
                        </div>
                        <div class="card-body">
                            <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات خاصة بالطلب أو التوصيل..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card sticky-top" style="top: 100px;">
                        <div class="card-header">ملخص الطلب</div>
                        <div class="card-body">
                            @foreach($cartItems as $item)
                            <div class="d-flex gap-2 mb-2">
                                <img src="{{ $item->product->image_url }}" alt="" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <small class="d-block">{{ Str::limit($item->product->name, 30) }}</small>
                                    <small class="text-muted">{{ $item->quantity }}x {{ number_format($item->unit_price, 2) }} ر.س</small>
                                </div>
                            </div>
                            @endforeach
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">المجموع الفرعي</span>
                                <span>{{ number_format($subtotal, 2) }} ر.س</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">الشحن</span>
                                <span>{{ $shipping > 0 ? number_format($shipping, 2) . ' ر.س' : 'مجاني' }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>الخصم</span>
                                <span>- {{ number_format($discount, 2) }} ر.س</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between fw-bold mb-3">
                                <span>الإجمالي</span>
                                <span class="text-primary fs-5">{{ number_format($total, 2) }} ر.س</span>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-1"></i> تأكيد الطلب
                                </button>
                            </div>
                            
                            <p class="text-muted small text-center mt-3 mb-0">
                                <i class="bi bi-lock me-1"></i>
                                معاملاتك آمنة ومشفرة
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
