<x-app-layout>
    <x-slot name="title">قائمة الرغبات</x-slot>
    
    <div class="container py-5">
        <h2 class="fw-bold mb-4">قائمة الرغبات</h2>
        
        @if($wishlistItems && $wishlistItems->count() > 0)
        <div class="row g-4">
            @foreach($wishlistItems as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="{{ $item->product->image_url }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 200px; object-fit: cover;">
                        <form action="{{ route('wishlist.remove', $item) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger rounded-circle">
                                <i class="bi bi-x"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('stores.show', $item->product->store) }}" class="text-muted small text-decoration-none">
                            {{ $item->product->store->name }}
                        </a>
                        <h6 class="card-title mt-1">
                            <a href="{{ route('products.show', $item->product) }}" class="text-dark text-decoration-none">
                                {{ $item->product->name }}
                            </a>
                        </h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            @if($item->product->isOnSale())
                            <div>
                                <span class="text-danger fw-bold">{{ number_format($item->product->sale_price, 2) }} ر.س</span>
                                <small class="text-muted text-decoration-line-through d-block">{{ number_format($item->product->price, 2) }} ر.س</small>
                            </div>
                            @else
                            <span class="text-primary fw-bold">{{ number_format($item->product->price, 2) }} ر.س</span>
                            @endif
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-heart fs-1 text-muted d-block mb-3"></i>
            <h5>قائمة الرغبات فارغة</h5>
            <p class="text-muted mb-3">لم تضف أي منتجات للمفضلة بعد</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-grid me-1"></i> تصفح المنتجات
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
