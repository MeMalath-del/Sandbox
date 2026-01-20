<x-app-layout>
    <x-slot name="title">المنتجات</x-slot>
    
    <div class="container py-5">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-funnel me-2"></i> فلترة النتائج
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET">
                            <!-- Categories -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">التصنيفات</h6>
                                @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $category->id }}" id="cat{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex justify-content-between" for="cat{{ $category->id }}">
                                        {{ $category->name }}
                                        <span class="badge bg-light text-dark">{{ $category->products_count }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Brands -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">الماركات</h6>
                                @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="brand" value="{{ $brand->id }}" id="brand{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex justify-content-between" for="brand{{ $brand->id }}">
                                        {{ $brand->name }}
                                        <span class="badge bg-light text-dark">{{ $brand->products_count }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Price Range -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">نطاق السعر</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" name="min_price" placeholder="من" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" name="max_price" placeholder="إلى" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Condition -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">الحالة</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" value="new" id="condNew" {{ request('condition') == 'new' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condNew">جديد</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" value="used" id="condUsed" {{ request('condition') == 'used' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condUsed">مستعمل</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="condition" value="refurbished" id="condRef" {{ request('condition') == 'refurbished' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="condRef">مجدد</label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i> تطبيق الفلتر
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> إعادة تعيين
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">المنتجات</h4>
                        <p class="text-muted mb-0">{{ $products->total() }} منتج</p>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" onchange="location = this.value" style="width: auto;">
                            <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'newest'])) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                            <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                            <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'rating'])) }}" {{ request('sort') == 'rating' ? 'selected' : '' }}>التقييم</option>
                            <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'bestseller'])) }}" {{ request('sort') == 'bestseller' ? 'selected' : '' }}>الأكثر مبيعاً</option>
                        </select>
                    </div>
                </div>
                
                <!-- Products -->
                @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-6 col-md-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                    <h5>لا توجد منتجات</h5>
                    <p class="text-muted">جرب تغيير معايير البحث</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
