@props(['product'])

<div class="card product-card h-100">
    <div class="position-relative">
        <a href="{{ route('products.show', $product) }}">
            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
        </a>
        
        @if($product->isOnSale())
        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
            -{{ $product->discount_percentage }}%
        </span>
        @elseif($product->is_featured)
        <span class="badge bg-warning position-absolute top-0 start-0 m-2">مميز</span>
        @elseif($product->is_new_arrival)
        <span class="badge bg-success position-absolute top-0 start-0 m-2">جديد</span>
        @endif
        
        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle" 
                onclick="event.preventDefault(); toggleWishlist({{ $product->id }})"
                title="إضافة للمفضلة">
            <i class="bi bi-heart"></i>
        </button>
    </div>
    
    <div class="card-body">
        <a href="{{ route('stores.show', $product->store) }}" class="text-muted small text-decoration-none">
            {{ $product->store->name }}
        </a>
        
        <h6 class="card-title mt-1 mb-2">
            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                {{ Str::limit($product->name, 50) }}
            </a>
        </h6>
        
        <div class="d-flex align-items-center mb-2">
            <div class="text-warning small">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($product->rating))
                    <i class="bi bi-star-fill"></i>
                    @else
                    <i class="bi bi-star"></i>
                    @endif
                @endfor
            </div>
            <span class="text-muted small me-2">({{ $product->rating_count }})</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if($product->isOnSale())
                <span class="text-muted text-decoration-line-through small d-block">
                    {{ number_format($product->price, 2) }} ر.س
                </span>
                <span class="fw-bold text-danger">
                    {{ number_format($product->sale_price, 2) }} ر.س
                </span>
                @else
                <span class="fw-bold text-primary">
                    {{ number_format($product->price, 2) }} ر.س
                </span>
                @endif
            </div>
            
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-sm" @if(!$product->isInStock()) disabled @endif>
                    <i class="bi bi-cart-plus"></i>
                </button>
            </form>
        </div>
        
        @if(!$product->isInStock())
        <small class="text-danger">
            <i class="bi bi-x-circle"></i> غير متوفر
        </small>
        @elseif($product->isLowStock())
        <small class="text-warning">
            <i class="bi bi-exclamation-triangle"></i> كمية محدودة
        </small>
        @endif
    </div>
</div>
