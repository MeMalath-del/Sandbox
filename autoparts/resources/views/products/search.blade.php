<x-app-layout>
    <x-slot name="title">نتائج البحث: {{ $query }}</x-slot>
    
    <div class="container py-5">
        <div class="mb-4">
            <h4 class="fw-bold">نتائج البحث عن: "{{ $query }}"</h4>
            <p class="text-muted">تم العثور على {{ $products->total() }} نتيجة</p>
        </div>
        
        @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
            <h5>لم يتم العثور على نتائج</h5>
            <p class="text-muted">جرب استخدام كلمات بحث مختلفة</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-grid me-1"></i> تصفح جميع المنتجات
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
