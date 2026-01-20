<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>
    
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">المنتجات</a></li>
                @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>
        
        <div class="row g-4">
            <!-- Product Images Gallery -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <!-- Main Image with Zoom -->
                    <div class="main-image-container">
                        <div class="zoom-wrapper" id="zoomWrapper">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="main-image" id="mainImage">
                            <div class="zoom-lens" id="zoomLens"></div>
                        </div>
                        <div class="zoom-result" id="zoomResult"></div>
                        
                        <!-- Badges -->
                        <div class="product-detail-badges">
                            @if($product->isOnSale())
                            <span class="badge bg-danger">خصم {{ $product->discount_percentage }}%</span>
                            @endif
                            @if($product->is_new_arrival)
                            <span class="badge bg-success">جديد</span>
                            @endif
                        </div>
                        
                        <!-- Fullscreen Button -->
                        <button class="fullscreen-btn" data-bs-toggle="modal" data-bs-target="#imageModal">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button>
                    </div>
                    
                    <!-- Thumbnails -->
                    @if($product->images->count() > 0)
                    <div class="thumbnails-container mt-3">
                        <div class="swiper thumbnails-swiper">
                            <div class="swiper-wrapper">
                                @foreach($product->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="thumbnail-img {{ $loop->first ? 'active' : '' }}"
                                         onclick="changeMainImage(this, '{{ asset('storage/' . $image->image_path) }}')">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Store -->
                    <a href="{{ route('stores.show', $product->store) }}" class="store-link">
                        <img src="{{ $product->store->logo_url }}" alt="{{ $product->store->name }}" class="store-logo-sm">
                        <span>{{ $product->store->name }}</span>
                        @if($product->store->is_verified)
                        <i class="bi bi-patch-check-fill text-primary"></i>
                        @endif
                    </a>
                    
                    <!-- Title -->
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <!-- Rating & Stats -->
                    <div class="product-stats">
                        <div class="rating-display">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product->rating))
                                    <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                    <i class="bi bi-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-text">{{ number_format($product->rating, 1) }} ({{ $product->rating_count }} تقييم)</span>
                        </div>
                        <span class="divider">|</span>
                        <span class="stat"><i class="bi bi-eye"></i> {{ $product->views_count }} مشاهدة</span>
                        <span class="divider">|</span>
                        <span class="stat"><i class="bi bi-bag-check"></i> {{ $product->sales_count }} عملية بيع</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="price-box">
                        @if($product->isOnSale())
                        <div class="sale-price">
                            <span class="current-price">{{ number_format($product->sale_price, 2) }} ر.س</span>
                            <span class="original-price">{{ number_format($product->price, 2) }} ر.س</span>
                            <span class="discount-badge">وفر {{ number_format($product->price - $product->sale_price, 2) }} ر.س</span>
                        </div>
                        @if($product->sale_ends_at)
                        <div class="sale-timer">
                            <i class="bi bi-clock"></i>
                            ينتهي العرض: <span id="saleTimer">{{ $product->sale_ends_at->diffForHumans() }}</span>
                        </div>
                        @endif
                        @else
                        <span class="current-price">{{ number_format($product->price, 2) }} ر.س</span>
                        @endif
                        <small class="text-muted">شامل الضريبة</small>
                    </div>
                    
                    <!-- Short Description -->
                    @if($product->short_description)
                    <p class="short-description">{{ $product->short_description }}</p>
                    @endif
                    
                    <!-- Stock Status -->
                    <div class="stock-status">
                        @if($product->isInStock())
                        <span class="in-stock">
                            <i class="bi bi-check-circle-fill"></i> متوفر في المخزون
                            @if($product->isLowStock())
                            <span class="low-stock-warning">- {{ $product->available_quantity }} قطعة متبقية فقط!</span>
                            @endif
                        </span>
                        @else
                        <span class="out-of-stock">
                            <i class="bi bi-x-circle-fill"></i> غير متوفر حالياً
                        </span>
                        @endif
                    </div>
                    
                    <!-- Quantity & Add to Cart -->
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-section">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="quantity-selector">
                            <label>الكمية:</label>
                            <div class="quantity-input">
                                <button type="button" class="qty-btn minus" onclick="changeQty(-1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" 
                                       min="{{ $product->min_order_quantity ?? 1 }}" 
                                       max="{{ min($product->max_order_quantity ?? 100, $product->available_quantity) }}">
                                <button type="button" class="qty-btn plus" onclick="changeQty(1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary btn-lg add-cart-btn" @if(!$product->isInStock()) disabled @endif>
                                <i class="bi bi-cart-plus me-2"></i> أضف إلى السلة
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg buy-now-btn" @if(!$product->isInStock()) disabled @endif onclick="buyNow()">
                                <i class="bi bi-lightning me-2"></i> اشتر الآن
                            </button>
                        </div>
                    </form>
                    
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <button onclick="addToWishlist({{ $product->id }})" class="action-btn">
                            <i class="bi bi-heart"></i> أضف للمفضلة
                        </button>
                        <button onclick="addToCompare({{ $product->id }}, '{{ $product->name }}', '{{ $product->image_url }}')" class="action-btn">
                            <i class="bi bi-arrow-left-right"></i> مقارنة
                        </button>
                        <button onclick="shareProduct()" class="action-btn">
                            <i class="bi bi-share"></i> مشاركة
                        </button>
                    </div>
                    
                    <!-- Product Meta -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">رقم القطعة (SKU):</span>
                            <span class="meta-value">{{ $product->sku }}</span>
                            <button class="copy-btn" onclick="copyToClipboard('{{ $product->sku }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        @if($product->oem_number)
                        <div class="meta-item">
                            <span class="meta-label">رقم OEM:</span>
                            <span class="meta-value">{{ $product->oem_number }}</span>
                            <button class="copy-btn" onclick="copyToClipboard('{{ $product->oem_number }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        @endif
                        @if($product->brand)
                        <div class="meta-item">
                            <span class="meta-label">الماركة:</span>
                            <a href="{{ route('brands.show', $product->brand) }}" class="meta-value text-primary">{{ $product->brand->name }}</a>
                        </div>
                        @endif
                        <div class="meta-item">
                            <span class="meta-label">الحالة:</span>
                            <span class="meta-value">
                                @if($product->condition == 'new')
                                <span class="badge bg-success">جديد</span>
                                @elseif($product->condition == 'used')
                                <span class="badge bg-warning text-dark">مستعمل</span>
                                @else
                                <span class="badge bg-info">مجدد</span>
                                @endif
                            </span>
                        </div>
                        @if($product->warranty_months)
                        <div class="meta-item">
                            <span class="meta-label">الضمان:</span>
                            <span class="meta-value">{{ $product->warranty_months }} شهر</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Delivery Info -->
                    <div class="delivery-info">
                        <div class="info-item">
                            <i class="bi bi-truck"></i>
                            <div>
                                <strong>التوصيل السريع</strong>
                                <small>2-5 أيام عمل</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <strong>ضمان الجودة</strong>
                                <small>منتجات أصلية 100%</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>
                                <strong>استرجاع مجاني</strong>
                                <small>خلال 14 يوم</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Details Tabs -->
        <div class="product-tabs mt-5">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description">
                        <i class="bi bi-file-text me-2"></i> الوصف
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specifications">
                        <i class="bi bi-list-check me-2"></i> المواصفات
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#compatibility">
                        <i class="bi bi-car-front me-2"></i> التوافقية
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">
                        <i class="bi bi-star me-2"></i> التقييمات ({{ $product->rating_count }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#questions">
                        <i class="bi bi-chat-dots me-2"></i> الأسئلة
                    </button>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Description -->
                <div class="tab-pane fade show active" id="description">
                    <div class="description-content">
                        {!! $product->description ?? '<p class="text-muted">لا يوجد وصف متاح لهذا المنتج.</p>' !!}
                    </div>
                </div>
                
                <!-- Specifications -->
                <div class="tab-pane fade" id="specifications">
                    @if($product->specifications->count() > 0)
                    <table class="table specifications-table">
                        <tbody>
                            @foreach($product->specifications as $spec)
                            <tr>
                                <th>{{ $spec->name }}</th>
                                <td>{{ $spec->display_value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-muted">لا توجد مواصفات متاحة لهذا المنتج.</p>
                    @endif
                </div>
                
                <!-- Compatibility -->
                <div class="tab-pane fade" id="compatibility">
                    @if($product->compatibilities->count() > 0)
                    <div class="compatibility-list">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الشركة المصنعة</th>
                                    <th>الموديل</th>
                                    <th>سنوات الصنع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->compatibilities as $comp)
                                <tr>
                                    <td>{{ $comp->carMake->name ?? '-' }}</td>
                                    <td>{{ $comp->carModel->name ?? '-' }}</td>
                                    <td>{{ $comp->year_from ?? '-' }} - {{ $comp->year_to ?? 'حتى الآن' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">لا توجد معلومات توافقية متاحة.</p>
                    @endif
                </div>
                
                <!-- Reviews -->
                <div class="tab-pane fade" id="reviews">
                    <div class="reviews-section">
                        <!-- Rating Summary -->
                        <div class="rating-summary">
                            <div class="overall-rating">
                                <span class="rating-number">{{ number_format($product->rating, 1) }}</span>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= round($product->rating) ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span class="total-reviews">{{ $product->rating_count }} تقييم</span>
                            </div>
                        </div>
                        
                        <!-- Reviews List -->
                        @if($reviews->count() > 0)
                        <div class="reviews-list">
                            @foreach($reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <img src="{{ $review->user->avatar_url }}" alt="" class="reviewer-avatar">
                                    <div class="reviewer-info">
                                        <strong>{{ $review->user->name }}</strong>
                                        <div class="review-meta">
                                            <span class="stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </span>
                                            <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="review-content">{{ $review->comment }}</p>
                            </div>
                            @endforeach
                        </div>
                        {{ $reviews->links() }}
                        @else
                        <p class="text-muted text-center py-4">لا توجد تقييمات بعد. كن أول من يقيم هذا المنتج!</p>
                        @endif
                    </div>
                </div>
                
                <!-- Questions -->
                <div class="tab-pane fade" id="questions">
                    <p class="text-muted">قريباً - قسم الأسئلة والأجوبة</p>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <section class="related-products mt-5">
            <div class="section-header">
                <h2><i class="bi bi-grid"></i> منتجات ذات صلة</h2>
                <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="view-all">
                    عرض المزيد <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-4 col-6">
                    @include('components.product-card', ['product' => $relatedProduct])
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
    
    <!-- Fullscreen Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid" id="modalImage">
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .product-gallery {
            position: sticky;
            top: 100px;
        }
        
        .main-image-container {
            position: relative;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 30px rgba(0,0,0,0.08);
        }
        
        .zoom-wrapper {
            position: relative;
            cursor: zoom-in;
        }
        
        .main-image {
            width: 100%;
            height: 500px;
            object-fit: contain;
            padding: 20px;
        }
        
        .zoom-result {
            position: absolute;
            top: 0;
            left: calc(100% + 20px);
            width: 400px;
            height: 400px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            background-repeat: no-repeat;
            display: none;
            z-index: 100;
        }
        
        .product-detail-badges {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .product-detail-badges .badge {
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 25px;
        }
        
        .fullscreen-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .fullscreen-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .thumbnails-container {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .thumbnail-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .thumbnail-img:hover,
        .thumbnail-img.active {
            border-color: var(--primary-color);
        }
        
        .product-info {
            padding: 20px;
        }
        
        .store-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background: #f8fafc;
            border-radius: 50px;
            text-decoration: none;
            color: #1f2937;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .store-link:hover {
            background: #e5e7eb;
        }
        
        .store-logo-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .product-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .product-stats {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .rating-text {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .divider {
            color: #e5e7eb;
        }
        
        .stat {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .price-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .current-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
        }
        
        .original-price {
            font-size: 1.1rem;
            color: #9ca3af;
            text-decoration: line-through;
            margin-right: 10px;
        }
        
        .discount-badge {
            background: #fef2f2;
            color: #ef4444;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .sale-timer {
            margin-top: 10px;
            color: #ef4444;
            font-size: 0.9rem;
        }
        
        .short-description {
            color: #6b7280;
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .stock-status {
            margin-bottom: 20px;
        }
        
        .in-stock {
            color: #10b981;
            font-weight: 600;
        }
        
        .low-stock-warning {
            color: #f59e0b;
            font-size: 0.9rem;
        }
        
        .out-of-stock {
            color: #ef4444;
            font-weight: 600;
        }
        
        .add-to-cart-section {
            margin-bottom: 25px;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quantity-input {
            display: flex;
            align-items: center;
            background: #f3f4f6;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .qty-btn {
            width: 45px;
            height: 45px;
            border: none;
            background: transparent;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .qty-btn:hover {
            background: #e5e7eb;
        }
        
        .quantity-input input {
            width: 60px;
            height: 45px;
            border: none;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            background: transparent;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
        }
        
        .add-cart-btn,
        .buy-now-btn {
            flex: 1;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .quick-actions {
            display: flex;
            gap: 10px;
            padding: 20px 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        
        .action-btn {
            background: none;
            border: none;
            color: #6b7280;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            background: #f3f4f6;
            color: var(--primary-color);
        }
        
        .product-meta {
            margin-bottom: 20px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .meta-label {
            color: #6b7280;
            min-width: 150px;
        }
        
        .meta-value {
            font-weight: 600;
        }
        
        .copy-btn {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 5px;
        }
        
        .copy-btn:hover {
            color: var(--primary-color);
        }
        
        .delivery-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .info-item i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .info-item strong {
            display: block;
            font-size: 0.9rem;
        }
        
        .info-item small {
            color: #6b7280;
            font-size: 0.8rem;
        }
        
        .product-tabs {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .product-tabs .nav-tabs {
            border: none;
            background: #f8fafc;
            padding: 15px 20px 0;
        }
        
        .product-tabs .nav-link {
            border: none;
            color: #6b7280;
            padding: 15px 25px;
            font-weight: 600;
            border-radius: 15px 15px 0 0;
        }
        
        .product-tabs .nav-link.active {
            background: white;
            color: var(--primary-color);
        }
        
        .product-tabs .tab-content {
            padding: 30px;
        }
        
        .specifications-table th {
            background: #f8fafc;
            width: 200px;
            font-weight: 600;
        }
        
        .rating-summary {
            display: flex;
            gap: 40px;
            padding: 30px;
            background: #f8fafc;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .overall-rating {
            text-align: center;
        }
        
        .rating-number {
            font-size: 4rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
        }
        
        .total-reviews {
            color: #6b7280;
        }
        
        .review-item {
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .review-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .review-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #6b7280;
            font-size: 0.85rem;
        }
        
        @media (max-width: 991px) {
            .main-image {
                height: 350px;
            }
            
            .zoom-result {
                display: none !important;
            }
            
            .delivery-info {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
    
    @push('scripts')
    <script>
        // Thumbnails Swiper
        new Swiper('.thumbnails-swiper', {
            slidesPerView: 5,
            spaceBetween: 10,
            breakpoints: {
                320: { slidesPerView: 4 },
                768: { slidesPerView: 5 },
            },
        });
        
        // Change Main Image
        function changeMainImage(thumb, src) {
            document.querySelectorAll('.thumbnail-img').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            document.getElementById('mainImage').src = src;
            document.getElementById('modalImage').src = src;
        }
        
        // Quantity Change
        function changeQty(delta) {
            const input = document.getElementById('quantity');
            const min = parseInt(input.min);
            const max = parseInt(input.max);
            let value = parseInt(input.value) + delta;
            if (value >= min && value <= max) {
                input.value = value;
            }
        }
        
        // Buy Now
        function buyNow() {
            const form = document.querySelector('.add-to-cart-section');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'buy_now';
            input.value = '1';
            form.appendChild(input);
            form.submit();
        }
        
        // Copy to Clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('success', 'تم النسخ بنجاح');
            });
        }
        
        // Share Product
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $product->name }}',
                    url: window.location.href
                });
            } else {
                copyToClipboard(window.location.href);
            }
        }
        
        // Zoom Effect (Desktop)
        if (window.innerWidth > 991) {
            const wrapper = document.getElementById('zoomWrapper');
            const result = document.getElementById('zoomResult');
            const img = document.getElementById('mainImage');
            
            wrapper.addEventListener('mouseenter', function() {
                result.style.display = 'block';
                result.style.backgroundImage = `url(${img.src})`;
            });
            
            wrapper.addEventListener('mouseleave', function() {
                result.style.display = 'none';
            });
            
            wrapper.addEventListener('mousemove', function(e) {
                const rect = wrapper.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;
                
                result.style.backgroundPosition = `${xPercent}% ${yPercent}%`;
                result.style.backgroundSize = '200%';
            });
        }
    </script>
    @endpush
</x-app-layout>
