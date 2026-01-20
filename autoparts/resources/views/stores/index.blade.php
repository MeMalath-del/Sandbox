<x-app-layout>
    <x-slot name="title">المتاجر</x-slot>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">المتاجر</h2>
                <p class="text-muted mb-0">{{ $stores->total() }} متجر</p>
            </div>
        </div>
        
        @if($stores->count() > 0)
        <div class="row g-4">
            @foreach($stores as $store)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('stores.show', $store) }}" class="text-decoration-none">
                    <div class="card h-100 product-card">
                        @if($store->cover_image)
                        <img src="{{ asset('storage/' . $store->cover_image) }}" class="card-img-top" alt="{{ $store->name }}" style="height: 120px; object-fit: cover;">
                        @else
                        <div class="bg-primary bg-gradient" style="height: 120px;"></div>
                        @endif
                        <div class="card-body text-center" style="margin-top: -50px;">
                            <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="rounded-circle border border-3 border-white mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                            <h5 class="mb-1 text-dark">{{ $store->name }}</h5>
                            <p class="text-muted small mb-2">{{ Str::limit($store->short_description ?? $store->description, 60) }}</p>
                            <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i> {{ number_format($store->rating, 1) }}
                                </span>
                                <span class="text-muted">{{ $store->products_count }} منتج</span>
                            </div>
                            @if($store->is_verified)
                            <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i> موثق</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $stores->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-shop fs-1 text-muted d-block mb-3"></i>
            <h5>لا توجد متاجر</h5>
        </div>
        @endif
    </div>
</x-app-layout>
