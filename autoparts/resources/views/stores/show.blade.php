<x-app-layout>
    <x-slot name="title">{{ $store->name }}</x-slot>
    
    <div class="container py-5">
        <!-- Store Header -->
        <div class="card mb-4">
            @if($store->cover_image)
            <img src="{{ asset('storage/' . $store->cover_image) }}" class="card-img-top" alt="{{ $store->name }}" style="height: 200px; object-fit: cover;">
            @else
            <div class="bg-primary bg-gradient" style="height: 200px;"></div>
            @endif
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="rounded-circle border border-3 border-white" style="width: 100px; height: 100px; object-fit: cover; margin-top: -60px;">
                            <div>
                                <h3 class="fw-bold mb-1">
                                    {{ $store->name }}
                                    @if($store->is_verified)
                                    <span class="badge bg-success ms-2"><i class="bi bi-patch-check-fill"></i></span>
                                    @endif
                                </h3>
                                <p class="text-muted mb-2">{{ $store->short_description }}</p>
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="bi bi-star-fill text-warning me-1"></i> {{ number_format($store->rating, 1) }} ({{ $store->rating_count }} تقييم)</span>
                                    <span><i class="bi bi-box-seam me-1"></i> {{ $store->products_count }} منتج</span>
                                    <span><i class="bi bi-people me-1"></i> {{ $store->followers_count ?? 0 }} متابع</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <form action="{{ route('stores.follow', $store) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> متابعة
                            </button>
                        </form>
                        <a href="{{ route('messages.create', ['store' => $store->id]) }}" class="btn btn-outline-primary">
                            <i class="bi bi-chat me-1"></i> تواصل
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card mb-3">
                    <div class="card-header">معلومات المتجر</div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($store->city)
                            <li class="mb-2">
                                <i class="bi bi-geo-alt text-muted me-2"></i> {{ $store->city }}
                            </li>
                            @endif
                            @if($store->phone)
                            <li class="mb-2">
                                <i class="bi bi-telephone text-muted me-2"></i> {{ $store->phone }}
                            </li>
                            @endif
                            @if($store->email)
                            <li class="mb-2">
                                <i class="bi bi-envelope text-muted me-2"></i> {{ $store->email }}
                            </li>
                            @endif
                            <li class="mb-2">
                                <i class="bi bi-calendar text-muted me-2"></i> انضم {{ $store->created_at->diffForHumans() }}
                            </li>
                        </ul>
                    </div>
                </div>
                
                @if($store->description)
                <div class="card">
                    <div class="card-header">نبذة عن المتجر</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $store->description }}</p>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Products -->
            <div class="col-lg-9">
                <h5 class="fw-bold mb-3">منتجات المتجر</h5>
                @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-6 col-md-4">
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
                    <h5>لا توجد منتجات في هذا المتجر</h5>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
