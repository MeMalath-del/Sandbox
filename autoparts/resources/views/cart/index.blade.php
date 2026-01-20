<x-app-layout>
    <x-slot name="title">سلة التسوق</x-slot>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-cart3 me-2"></i> سلة التسوق
            </h2>
            @if($cartItems && $cartItems->count() > 0)
            <span class="text-muted">{{ $cartItems->count() }} منتج</span>
            @endif
        </div>
        
        @if($cartItems && $cartItems->count() > 0)
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items">
                    @foreach($cartItems as $item)
                    <div class="cart-item" id="cartItem{{ $item->id }}">
                        <div class="item-image">
                            <a href="{{ route('products.show', $item->product) }}">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                            </a>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <div>
                                    <a href="{{ route('stores.show', $item->product->store) }}" class="store-name">
                                        <i class="bi bi-shop"></i> {{ $item->product->store->name }}
                                    </a>
                                    <h5 class="item-name">
                                        <a href="{{ route('products.show', $item->product) }}">{{ $item->product->name }}</a>
                                    </h5>
                                    <div class="item-meta">
                                        <span><strong>SKU:</strong> {{ $item->product->sku }}</span>
                                        @if($item->product->isOnSale())
                                        <span class="badge bg-danger">خصم {{ $item->product->discount_percentage }}%</span>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('cart.remove', $item) }}" method="POST" class="remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove" title="حذف">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <div class="item-footer">
                                <div class="quantity-control">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="qty-btn" onclick="updateQty(this, -1)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="qty-input" onchange="this.form.submit()">
                                        <button type="button" class="qty-btn" onclick="updateQty(this, 1)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="item-price">
                                    @if($item->product->isOnSale())
                                    <span class="old-price">{{ number_format($item->product->price * $item->quantity, 2) }} ر.س</span>
                                    @endif
                                    <span class="current-price">{{ number_format($item->subtotal, 2) }} ر.س</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Cart Actions -->
                <div class="cart-actions">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-right me-2"></i> متابعة التسوق
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إفراغ السلة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-2"></i> إفراغ السلة
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h5 class="summary-title">ملخص الطلب</h5>
                    
                    <div class="summary-row">
                        <span>المجموع الفرعي ({{ $cartItems->sum('quantity') }} منتج)</span>
                        <span>{{ number_format($subtotal, 2) }} ر.س</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>
                            <i class="bi bi-truck me-1"></i> الشحن
                            @if($shipping == 0)
                            <span class="badge bg-success ms-1">مجاني</span>
                            @endif
                        </span>
                        <span>{{ $shipping > 0 ? number_format($shipping, 2) . ' ر.س' : 'مجاني' }}</span>
                    </div>
                    
                    @if($discount > 0)
                    <div class="summary-row discount">
                        <span><i class="bi bi-tag me-1"></i> الخصم</span>
                        <span>- {{ number_format($discount, 2) }} ر.س</span>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="summary-row total">
                        <span>الإجمالي</span>
                        <span>{{ number_format($total, 2) }} ر.س</span>
                    </div>
                    
                    <small class="text-muted d-block mb-3">شامل ضريبة القيمة المضافة</small>
                    
                    <!-- Coupon -->
                    <div class="coupon-section">
                        <form action="{{ route('cart.applyCoupon') }}" method="POST" class="coupon-form">
                            @csrf
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-percent"></i></span>
                                <input type="text" name="coupon_code" class="form-control" placeholder="كود الخصم" value="{{ session('cart_coupon') }}">
                                <button type="submit" class="btn btn-outline-primary">تطبيق</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 checkout-btn">
                        <i class="bi bi-lock me-2"></i> إتمام الشراء
                    </a>
                    
                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="badge-item">
                            <i class="bi bi-shield-check"></i>
                            <span>دفع آمن 100%</span>
                        </div>
                        <div class="badge-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>استرجاع مجاني</span>
                        </div>
                        <div class="badge-item">
                            <i class="bi bi-headset"></i>
                            <span>دعم 24/7</span>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Info -->
                @if($subtotal < 200)
                <div class="shipping-info">
                    <div class="progress-bar-container">
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: {{ ($subtotal / 200) * 100 }}%"></div>
                        </div>
                        <p class="mt-2 mb-0">
                            <i class="bi bi-truck me-1"></i>
                            أضف منتجات بقيمة <strong>{{ number_format(200 - $subtotal, 2) }} ر.س</strong> للحصول على شحن مجاني!
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h4>سلة التسوق فارغة</h4>
            <p class="text-muted">لم تضف أي منتجات بعد. ابدأ التسوق الآن!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid me-2"></i> تصفح المنتجات
            </a>
        </div>
        @endif
    </div>
    
    <style>
        .cart-items {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .cart-item {
            display: flex;
            gap: 20px;
            padding: 25px;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.3s;
        }
        
        .cart-item:hover {
            background: #fafafa;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            flex-shrink: 0;
        }
        
        .item-image img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .store-name {
            color: #6b7280;
            font-size: 0.85rem;
            text-decoration: none;
        }
        
        .store-name:hover {
            color: var(--primary-color);
        }
        
        .item-name {
            margin: 5px 0 10px;
            font-size: 1.1rem;
        }
        
        .item-name a {
            color: #1f2937;
            text-decoration: none;
        }
        
        .item-name a:hover {
            color: var(--primary-color);
        }
        
        .item-meta {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
            color: #6b7280;
        }
        
        .btn-remove {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 1.2rem;
            padding: 5px;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .btn-remove:hover {
            color: #ef4444;
        }
        
        .item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
        }
        
        .qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .qty-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .qty-input {
            width: 50px;
            height: 36px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
        }
        
        .item-price {
            text-align: left;
        }
        
        .old-price {
            display: block;
            color: #9ca3af;
            text-decoration: line-through;
            font-size: 0.9rem;
        }
        
        .current-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .order-summary {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            position: sticky;
            top: 100px;
        }
        
        .summary-title {
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        
        .summary-row.discount {
            color: #10b981;
        }
        
        .summary-row.total {
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .summary-row.total span:last-child {
            color: var(--primary-color);
        }
        
        .coupon-section {
            margin: 20px 0;
        }
        
        .checkout-btn {
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
        }
        
        .trust-badges {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .badge-item {
            text-align: center;
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        .badge-item i {
            display: block;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .shipping-info {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .shipping-info .progress {
            height: 8px;
            border-radius: 10px;
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
        }
        
        .empty-cart-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .empty-cart-icon i {
            font-size: 4rem;
            color: #9ca3af;
        }
        
        @media (max-width: 767px) {
            .cart-item {
                flex-direction: column;
            }
            
            .item-image img {
                width: 100%;
                height: 200px;
            }
            
            .item-footer {
                flex-direction: column;
                gap: 15px;
            }
            
            .item-price {
                text-align: center;
            }
        }
    </style>
    
    <script>
        function updateQty(btn, delta) {
            const form = btn.closest('form');
            const input = form.querySelector('input[name="quantity"]');
            let value = parseInt(input.value) + delta;
            if (value >= 1) {
                input.value = value;
                form.submit();
            }
        }
    </script>
</x-app-layout>
