<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>
    
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
                @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
        
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded" id="mainImage">
                        
                        @if($product->images->count() > 1)
                        <div class="d-flex gap-2 mt-3">
                            @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-thumbnail" 
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                 onclick="document.getElementById('mainImage').src = this.src">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <!-- Store -->
                        <a href="{{ route('stores.show', $product->store) }}" class="text-muted text-decoration-none">
                            <i class="bi bi-shop me-1"></i> {{ $product->store->name }}
                        </a>
                        
                        <!-- Title -->
                        <h2 class="fw-bold mt-2 mb-3">{{ $product->name }}</h2>
                        
                        <!-- Rating -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product->rating))
                                    <i class="bi bi-star-fill"></i>
                                    @else
                                    <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-muted">({{ $product->rating_count }} تقييم)</span>
                            <span class="mx-2">|</span>
                            <span class="text-muted">{{ $product->sales_count }} مبيعة</span>
                        </div>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            @if($product->isOnSale())
                            <span class="text-muted text-decoration-line-through fs-5">{{ number_format($product->price, 2) }} ر.س</span>
                            <span class="text-danger fs-3 fw-bold me-2">{{ number_format($product->sale_price, 2) }} ر.س</span>
                            <span class="badge bg-danger">خصم {{ $product->discount_percentage }}%</span>
                            @else
                            <span class="text-primary fs-3 fw-bold">{{ number_format($product->price, 2) }} ر.س</span>
                            @endif
                        </div>
                        
                        <!-- Stock Status -->
                        @if($product->isInStock())
                        <p class="text-success mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            متوفر في المخزون
                            @if($product->isLowStock())
                            <span class="text-warning">({{ $product->available_quantity }} قطعة متبقية)</span>
                            @endif
                        </p>
                        @else
                        <p class="text-danger mb-3">
                            <i class="bi bi-x-circle-fill me-1"></i>
                            غير متوفر حالياً
                        </p>
                        @endif
                        
                        <!-- Add to Cart -->
                        <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="d-flex gap-3 align-items-center">
                                <div class="input-group" style="width: 140px;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">-</button>
                                    <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="{{ $product->min_order_quantity }}" max="{{ $product->max_order_quantity ?? 100 }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1" @if(!$product->isInStock()) disabled @endif>
                                    <i class="bi bi-cart-plus me-2"></i> أضف إلى السلة
                                </button>
                            </div>
                        </form>
                        
                        <!-- Wishlist & Share -->
                        <div class="d-flex gap-2 mb-4">
                            <form action="{{ route('wishlist.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-heart me-1"></i> أضف للمفضلة
                                </button>
                            </form>
                            <button class="btn btn-outline-secondary" onclick="navigator.share({title: '{{ $product->name }}', url: window.location.href})">
                                <i class="bi bi-share me-1"></i> مشاركة
                            </button>
                        </div>
                        
                        <!-- Quick Info -->
                        <div class="border-top pt-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <p class="mb-1 text-muted small">رقم القطعة (SKU)</p>
                                    <p class="mb-0 fw-bold">{{ $product->sku }}</p>
                                </div>
                                @if($product->oem_number)
                                <div class="col-6">
                                    <p class="mb-1 text-muted small">رقم OEM</p>
                                    <p class="mb-0 fw-bold">{{ $product->oem_number }}</p>
                                </div>
                                @endif
                                @if($product->brand)
                                <div class="col-6">
                                    <p class="mb-1 text-muted small">الماركة</p>
                                    <p class="mb-0 fw-bold">{{ $product->brand->name }}</p>
                                </div>
                                @endif
                                <div class="col-6">
                                    <p class="mb-1 text-muted small">الحالة</p>
                                    <p class="mb-0 fw-bold">{{ $product->condition == 'new' ? 'جديد' : ($product->condition == 'used' ? 'مستعمل' : 'مجدد') }}</p>
                                </div>
                                @if($product->warranty_months)
                                <div class="col-6">
                                    <p class="mb-1 text-muted small">الضمان</p>
                                    <p class="mb-0 fw-bold">{{ $product->warranty_months }} شهر</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="card mt-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#description">الوصف</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#specifications">المواصفات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#compatibility">التوافقية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#reviews">التقييمات ({{ $product->rating_count }})</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="description">
                        {!! $product->description ?? '<p class="text-muted">لا يوجد وصف متاح</p>' !!}
                    </div>
                    
                    <div class="tab-pane fade" id="specifications">
                        @if($product->specifications->count() > 0)
                        <table class="table">
                            <tbody>
                                @foreach($product->specifications as $spec)
                                <tr>
                                    <th style="width: 200px;">{{ $spec->name }}</th>
                                    <td>{{ $spec->display_value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted">لا توجد مواصفات متاحة</p>
                        @endif
                    </div>
                    
                    <div class="tab-pane fade" id="compatibility">
                        @if($product->compatibilities->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الشركة</th>
                                    <th>الموديل</th>
                                    <th>السنوات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->compatibilities as $comp)
                                <tr>
                                    <td>{{ $comp->carMake->name ?? '-' }}</td>
                                    <td>{{ $comp->carModel->name ?? '-' }}</td>
                                    <td>{{ $comp->year_from ?? '-' }} - {{ $comp->year_to ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted">لا توجد معلومات توافقية</p>
                        @endif
                    </div>
                    
                    <div class="tab-pane fade" id="reviews">
                        @if($reviews->count() > 0)
                            @foreach($reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="avatar me-2">
                                        <div>
                                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                            @else
                                            <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="mt-2 mb-0">{{ $review->comment }}</p>
                            </div>
                            @endforeach
                            {{ $reviews->links() }}
                        @else
                        <p class="text-muted text-center py-4">لا توجد تقييمات بعد</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h4 class="fw-bold mb-4">منتجات ذات صلة</h4>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-6 col-md-3">
                    @include('components.product-card', ['product' => $relatedProduct])
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <script>
        function incrementQty() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max) || 100;
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }
        
        function decrementQty() {
            const input = document.getElementById('quantity');
            const min = parseInt(input.min) || 1;
            if (parseInt(input.value) > min) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</x-app-layout>
