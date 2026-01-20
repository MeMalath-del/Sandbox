<x-app-layout>
    <x-slot name="title">{{ $category->name }}</x-slot>
    
    <div class="container py-5">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
                    @if($category->parent)
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $category->parent) }}">{{ $category->parent->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <h2 class="fw-bold">{{ $category->name }}</h2>
            @if($category->description)
            <p class="text-muted">{{ $category->description }}</p>
            @endif
        </div>
        
        <!-- Subcategories -->
        @if($subcategories->count() > 0)
        <div class="row g-3 mb-4">
            @foreach($subcategories as $sub)
            <div class="col-6 col-md-3">
                <a href="{{ route('categories.show', $sub) }}" class="card text-decoration-none h-100">
                    <div class="card-body text-center">
                        <i class="bi {{ $sub->icon ?? 'bi-grid' }} fs-1 text-primary mb-2 d-block"></i>
                        <h6 class="mb-0">{{ $sub->name }}</h6>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
        
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
            <h5>لا توجد منتجات في هذا التصنيف</h5>
        </div>
        @endif
    </div>
</x-app-layout>
