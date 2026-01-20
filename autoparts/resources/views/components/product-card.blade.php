<div class="product-card h-100">
    <div class="card-img-wrapper">
        <!-- Product Image -->
        <a href="{{ route('products.show', $product) }}">
            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
        </a>
        
        <!-- Badges -->
        <div class="product-badges">
            @if($product->isOnSale())
            <span class="badge bg-danger">{{ $product->discount_percentage }}% خصم</span>
            @endif
            @if($product->is_new_arrival)
            <span class="badge bg-success">جديد</span>
            @endif
            @if($product->is_bestseller)
            <span class="badge bg-warning text-dark">الأكثر مبيعاً</span>
            @endif
            @if(!$product->isInStock())
            <span class="badge bg-secondary">نفذت الكمية</span>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="product-actions">
            <button class="btn" onclick="addToWishlist({{ $product->id }})" title="أضف للمفضلة">
                <i class="bi bi-heart"></i>
            </button>
            <button class="btn" onclick="quickView({{ $product->id }})" title="عرض سريع" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                <i class="bi bi-eye"></i>
            </button>
            <button class="btn" onclick="addToCompare({{ $product->id }}, '{{ $product->name }}', '{{ $product->image_url }}')" title="مقارنة">
                <i class="bi bi-arrow-left-right"></i>
            </button>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Store Name -->
        @if($product->store)
        <a href="{{ route('stores.show', $product->store) }}" class="store-name text-decoration-none">
            <i class="bi bi-shop"></i> {{ $product->store->name }}
        </a>
        @endif
        
        <!-- Product Title -->
        <h6 class="card-title">
            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        </h6>
        
        <!-- Rating -->
        @if($product->rating_count > 0)
        <div class="product-rating">
            <span class="stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($product->rating))
                    <i class="bi bi-star-fill"></i>
                    @elseif($i - 0.5 <= $product->rating)
                    <i class="bi bi-star-half"></i>
                    @else
                    <i class="bi bi-star"></i>
                    @endif
                @endfor
            </span>
            <span class="text-muted">({{ $product->rating_count }})</span>
        </div>
        @endif
        
        <!-- Price -->
        <div class="product-price">
            @if($product->isOnSale())
            <span class="current">{{ number_format($product->sale_price, 2) }} ر.س</span>
            <span class="old">{{ number_format($product->price, 2) }} ر.س</span>
            <span class="discount">وفر {{ number_format($product->price - $product->sale_price, 2) }} ر.س</span>
            @else
            <span class="current">{{ number_format($product->price, 2) }} ر.س</span>
            @endif
        </div>
        
        <!-- Add to Cart Button -->
        @if($product->isInStock())
        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary add-to-cart-btn">
                <i class="bi bi-cart-plus me-2"></i> أضف للسلة
            </button>
        </form>
        @else
        <button class="btn btn-secondary add-to-cart-btn" disabled>
            <i class="bi bi-x-circle me-2"></i> نفذت الكمية
        </button>
        @endif
    </div>
</div>
