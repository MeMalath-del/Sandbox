<x-dashboard-layout>
    <x-slot name="title">التقارير</x-slot>
    
    <h4 class="fw-bold mb-4">التقارير</h4>
    
    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('admin.reports.sales') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cash-coin fs-1 text-success mb-3 d-block"></i>
                    <h5>تقرير المبيعات</h5>
                    <p class="text-muted mb-0">عرض إحصائيات المبيعات والإيرادات</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.reports.users') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-primary mb-3 d-block"></i>
                    <h5>تقرير المستخدمين</h5>
                    <p class="text-muted mb-0">إحصائيات التسجيلات والنشاط</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.reports.products') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1 text-info mb-3 d-block"></i>
                    <h5>تقرير المنتجات</h5>
                    <p class="text-muted mb-0">إحصائيات المنتجات والمخزون</p>
                </div>
            </a>
        </div>
    </div>
</x-dashboard-layout>
