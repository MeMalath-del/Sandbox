@extends('layouts.app')

@section('title', 'البحث' . ($query ? ': ' . $query : ''))

@section('content')
<div class="container py-5">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <form action="{{ route('search') }}" method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control" value="{{ $query }}" placeholder="ابحث عن قطع غيار...">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-lg-4 mt-3 mt-lg-0 d-flex align-items-center justify-content-lg-end">
            <span class="text-muted">{{ number_format($products->total()) }} نتيجة</span>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>تصفية النتائج</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('search') }}" method="GET" id="filterForm">
                        <input type="hidden" name="q" value="{{ $query }}">

                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="small fw-bold">الفئة</h6>
                            @foreach($categories as $category)
                            <div class="form-check">
                                <input type="radio" name="category" value="{{ $category->id }}" class="form-check-input" id="cat{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small" for="cat{{ $category->id }}">
                                    {{ $category->name }} ({{ $category->products_count }})
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Brands -->
                        <div class="mb-4">
                            <h6 class="small fw-bold">الماركة</h6>
                            @foreach($brands as $brand)
                            <div class="form-check">
                                <input type="radio" name="brand" value="{{ $brand->id }}" class="form-check-input" id="brand{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small" for="brand{{ $brand->id }}">
                                    {{ $brand->name }} ({{ $brand->products_count }})
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="small fw-bold">السعر</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm" placeholder="من" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm" placeholder="إلى" value="{{ request('max_price') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100 mt-2">تطبيق</button>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <h6 class="small fw-bold">التقييم</h6>
                            @for($i = 4; $i >= 1; $i--)
                            <div class="form-check">
                                <input type="radio" name="rating" value="{{ $i }}" class="form-check-input" id="rating{{ $i }}" {{ request('rating') == $i ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small" for="rating{{ $i }}">
                                    @for($j = 1; $j <= 5; $j++)
                                    <i class="bi bi-star{{ $j <= $i ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                    وأعلى
                                </label>
                            </div>
                            @endfor
                        </div>

                        <!-- On Sale -->
                        <div class="form-check mb-3">
                            <input type="checkbox" name="on_sale" value="1" class="form-check-input" id="onSale" {{ request('on_sale') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="onSale">عروض فقط</label>
                        </div>

                        <a href="{{ route('search', ['q' => $query]) }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-x-circle me-1"></i>إزالة الفلاتر
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="col-lg-9">
            <!-- Sort -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div></div>
                <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="window.location.href = '{{ route('search', array_merge(request()->except('sort'), ['q' => $query])) }}&sort=' + this.value">
                    <option value="">ترتيب حسب</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
                    <option value="sales" {{ request('sort') === 'sales' ? 'selected' : '' }}>الأكثر مبيعاً</option>
                </select>
            </div>

            @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-6 col-md-4">
                    @include('components.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>

            {{ $products->appends(request()->query())->links() }}
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <h5 class="mt-3">لا توجد نتائج</h5>
                    <p class="text-muted">جرب البحث بكلمات مختلفة أو تصفح الفئات</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
