<x-app-layout>
    <x-slot name="title">سلة التسوق</x-slot>
    
    <div class="container py-5">
        <h2 class="fw-bold mb-4">سلة التسوق</h2>
        
        @if($cartItems && $cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                        <div class="d-flex gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('products.show', $item->product) }}" class="text-dark text-decoration-none">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ $item->product->store->name }}</p>
                                    </div>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="this.parentNode.querySelector('input').stepDown(); this.form.submit();">-</button>
                                            <input type="number" name="quantity" class="form-control text-center" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()">
                                            <button type="button" class="btn btn-outline-secondary" onclick="this.parentNode.querySelector('input').stepUp(); this.form.submit();">+</button>
                                        </div>
                                    </form>
                                    <span class="fw-bold text-primary">{{ number_format($item->subtotal, 2) }} ر.س</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-right me-1"></i> متابعة التسوق
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> إفراغ السلة
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">ملخص الطلب</div>
                    <div class="card-body">
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
                        <div class="d-flex justify-content-between fw-bold">
                            <span>الإجمالي</span>
                            <span class="text-primary fs-5">{{ number_format($total, 2) }} ر.س</span>
                        </div>
                        
                        <!-- Coupon -->
                        <form action="{{ route('cart.applyCoupon') }}" method="POST" class="mt-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" placeholder="كود الخصم">
                                <button type="submit" class="btn btn-outline-primary">تطبيق</button>
                            </div>
                        </form>
                        
                        <div class="d-grid mt-3">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-credit-card me-1"></i> إتمام الشراء
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted d-block mb-3"></i>
            <h5>سلة التسوق فارغة</h5>
            <p class="text-muted mb-3">لم تضف أي منتجات بعد</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-grid me-1"></i> تصفح المنتجات
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
