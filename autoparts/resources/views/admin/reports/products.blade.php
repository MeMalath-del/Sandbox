<x-dashboard-layout>
    <x-slot name="title">تقرير المنتجات</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تقرير المنتجات</h4>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للتقارير
        </a>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">إجمالي المنتجات</h6>
                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">منتجات نشطة</h6>
                    <h3 class="mb-0">{{ $stats['active'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50">قيد المراجعة</h6>
                    <h3 class="mb-0">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white-50">نفذت الكمية</h6>
                    <h3 class="mb-0">{{ $stats['out_of_stock'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">الأكثر مبيعاً</div>
        <div class="card-body">
            @if(isset($topProducts) && $topProducts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>المتجر</th>
                            <th>المبيعات</th>
                            <th>الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->store->name ?? '-' }}</td>
                            <td>{{ $product->sales_count }}</td>
                            <td>{{ number_format($product->sales_count * $product->price, 2) }} ر.س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted text-center py-4">لا توجد بيانات</p>
            @endif
        </div>
    </div>
</x-dashboard-layout>
