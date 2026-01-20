<x-app-layout>
    <x-slot name="title">{{ $brand->name }}</x-slot>
    
    <div class="container py-5">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
                    <li class="breadcrumb-item active">{{ $brand->name }}</li>
                </ol>
            </nav>
            
            <div class="d-flex align-items-center gap-3">
                @if($brand->logo)
                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 60px;">
                @endif
                <div>
                    <h2 class="fw-bold mb-0">{{ $brand->name }}</h2>
                    @if($brand->description)
                    <p class="text-muted mb-0">{{ $brand->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Products -->
        @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
            <h5>لا توجد منتجات لهذه الماركة</h5>
        </div>
        @endif
    </div>
</x-app-layout>
