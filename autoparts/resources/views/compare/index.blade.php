@extends('layouts.app')

@section('title', 'مقارنة المنتجات')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-grid-3x2 me-2"></i>مقارنة المنتجات</h1>
        @if($products->count() > 0)
        <button type="button" class="btn btn-outline-danger" onclick="clearComparison()">
            <i class="bi bi-trash me-2"></i>مسح الكل
        </button>
        @endif
    </div>

    @if($products->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <!-- Images -->
                <tr>
                    <th class="bg-light" style="width: 150px;">الصورة</th>
                    @foreach($products as $product)
                    <td class="text-center p-3" style="min-width: 250px;">
                        <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2" onclick="removeFromComparison({{ $product->id }})">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <img src="{{ $product->primaryImage?->url ?? '/images/placeholder.jpg' }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 150px;">
                    </td>
                    @endforeach
                </tr>

                <!-- Name -->
                <tr>
                    <th class="bg-light">اسم المنتج</th>
                    @foreach($products as $product)
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="text-dark fw-medium text-decoration-none">
                            {{ $product->name }}
                        </a>
                    </td>
                    @endforeach
                </tr>

                <!-- Price -->
                <tr>
                    <th class="bg-light">السعر</th>
                    @foreach($products as $product)
                    <td>
                        @if($product->sale_price)
                        <span class="text-danger fw-bold">{{ number_format($product->sale_price, 2) }} ر.س</span>
                        <del class="text-muted ms-2">{{ number_format($product->price, 2) }} ر.س</del>
                        @else
                        <span class="fw-bold">{{ number_format($product->price, 2) }} ر.س</span>
                        @endif
                    </td>
                    @endforeach
                </tr>

                <!-- Rating -->
                <tr>
                    <th class="bg-light">التقييم</th>
                    @foreach($products as $product)
                    <td>
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $product->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <small class="text-muted">({{ $product->reviews_count ?? 0 }} تقييم)</small>
                    </td>
                    @endforeach
                </tr>

                <!-- Brand -->
                <tr>
                    <th class="bg-light">الماركة</th>
                    @foreach($products as $product)
                    <td>{{ $product->brand?->name ?? 'غير محدد' }}</td>
                    @endforeach
                </tr>

                <!-- Category -->
                <tr>
                    <th class="bg-light">الفئة</th>
                    @foreach($products as $product)
                    <td>{{ $product->category?->name ?? 'غير محدد' }}</td>
                    @endforeach
                </tr>

                <!-- Store -->
                <tr>
                    <th class="bg-light">المتجر</th>
                    @foreach($products as $product)
                    <td>
                        <a href="{{ route('stores.show', $product->store) }}">{{ $product->store->name }}</a>
                    </td>
                    @endforeach
                </tr>

                <!-- Stock -->
                <tr>
                    <th class="bg-light">التوفر</th>
                    @foreach($products as $product)
                    <td>
                        @if($product->quantity > 0)
                        <span class="badge bg-success">متوفر</span>
                        @else
                        <span class="badge bg-danger">غير متوفر</span>
                        @endif
                    </td>
                    @endforeach
                </tr>

                <!-- Specifications -->
                @foreach($allSpecs as $spec)
                <tr>
                    <th class="bg-light">{{ $spec }}</th>
                    @foreach($products as $product)
                    <td>
                        {{ $product->specifications->firstWhere('name', $spec)?->value ?? '-' }}
                    </td>
                    @endforeach
                </tr>
                @endforeach

                <!-- Add to Cart -->
                <tr>
                    <th class="bg-light">الإجراء</th>
                    @foreach($products as $product)
                    <td>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary w-100" {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus me-2"></i>أضف للسلة
                            </button>
                        </form>
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-grid-3x2 fs-1 text-muted"></i>
            <h5 class="mt-3">لا توجد منتجات للمقارنة</h5>
            <p class="text-muted">أضف منتجات من صفحات المنتجات لمقارنتها</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-box-seam me-2"></i>تصفح المنتجات
            </a>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function removeFromComparison(productId) {
    fetch('{{ route('compare.remove') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
    }).then(() => location.reload());
}

function clearComparison() {
    fetch('{{ route('compare.clear') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => location.reload());
}
</script>
@endpush
@endsection
