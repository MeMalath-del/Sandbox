<x-dashboard-layout>
    <x-slot name="title">عرض المتجر</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تفاصيل المتجر</h4>
        <a href="{{ route('admin.stores') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للمتاجر
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5>{{ $store->name }}</h5>
                    <p class="text-muted">{{ $store->short_description ?? '-' }}</p>
                    <div class="mb-2">
                        <span class="badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ $store->status == 'active' ? 'نشط' : ($store->status == 'pending' ? 'معلق' : $store->status) }}
                        </span>
                        @if($store->is_verified)
                        <span class="badge bg-success"><i class="bi bi-patch-check-fill"></i> موثق</span>
                        @endif
                    </div>
                    <p class="mb-0">
                        <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                        {{ number_format($store->rating, 1) }} ({{ $store->rating_count }} تقييم)
                    </p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">إجراءات</div>
                <div class="card-body">
                    @if($store->status == 'pending')
                    <form action="{{ route('admin.stores.approve', $store) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i> قبول المتجر
                        </button>
                    </form>
                    @endif
                    @if($store->status == 'active')
                    <form action="{{ route('admin.stores.suspend', $store) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-lg me-1"></i> إيقاف المتجر
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">معلومات المتجر</div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>المالك:</th>
                            <td>{{ $store->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>البريد الإلكتروني:</th>
                            <td>{{ $store->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>رقم الجوال:</th>
                            <td>{{ $store->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>العنوان:</th>
                            <td>{{ $store->address ?? '-' }}, {{ $store->city ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>عدد المنتجات:</th>
                            <td>{{ $store->products->count() }}</td>
                        </tr>
                        <tr>
                            <th>عدد الطلبات:</th>
                            <td>{{ $store->orders->count() }}</td>
                        </tr>
                        <tr>
                            <th>إجمالي المبيعات:</th>
                            <td>{{ number_format($store->total_sales ?? 0, 2) }} ر.س</td>
                        </tr>
                        <tr>
                            <th>تاريخ التسجيل:</th>
                            <td>{{ $store->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($store->description)
            <div class="card">
                <div class="card-header">الوصف</div>
                <div class="card-body">
                    {{ $store->description }}
                </div>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
