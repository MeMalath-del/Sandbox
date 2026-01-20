<x-dashboard-layout>
    <x-slot name="title">لوحة الإدارة</x-slot>
    <x-slot name="header">لوحة الإدارة</x-slot>
    
    <x-slot name="sidebar">
        <div class="nav-section-title">الرئيسية</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">إدارة المستخدمين</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="bi bi-people"></i> المستخدمين
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.stores') }}">
                    <i class="bi bi-shop"></i> المتاجر
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.drivers') }}">
                    <i class="bi bi-truck"></i> الموصلين
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">المنتجات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.products') }}">
                    <i class="bi bi-box-seam"></i> المنتجات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-grid"></i> التصنيفات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.brands.index') }}">
                    <i class="bi bi-award"></i> الماركات
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">الطلبات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.orders') }}">
                    <i class="bi bi-bag"></i> الطلبات
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">التقارير</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.reports') }}">
                    <i class="bi bi-graph-up"></i> التقارير
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.reports.sales') }}">
                    <i class="bi bi-currency-dollar"></i> تقرير المبيعات
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">الإعدادات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.settings') }}">
                    <i class="bi bi-gear"></i> إعدادات النظام
                </a>
            </li>
        </ul>
    </x-slot>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">إجمالي المستخدمين</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">إجمالي المتاجر</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['total_stores'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-shop"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">إجمالي المنتجات</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['total_products'] ?? 0) }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">مبيعات هذا الشهر</p>
                        <h3 class="stat-value mb-0">{{ number_format($stats['this_month_sales'] ?? 0, 0) }} <small class="fs-6">ر.س</small></h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Items -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $stats['pending_stores'] ?? 0 }}</h3>
                    <p class="mb-0">متاجر بانتظار الموافقة</p>
                    <a href="{{ route('admin.stores') }}?status=pending" class="btn btn-sm btn-warning mt-2">مراجعة</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $stats['pending_drivers'] ?? 0 }}</h3>
                    <p class="mb-0">موصلين بانتظار الموافقة</p>
                    <a href="{{ route('admin.drivers') }}?status=pending" class="btn btn-sm btn-warning mt-2">مراجعة</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h3 class="text-warning">{{ $stats['pending_products'] ?? 0 }}</h3>
                    <p class="mb-0">منتجات بانتظار الموافقة</p>
                    <a href="{{ route('admin.products') }}?status=pending" class="btn btn-sm btn-warning mt-2">مراجعة</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>أحدث الطلبات</span>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>العميل</th>
                                    <th>المتجر</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->order_number }}</a></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->store->name }}</td>
                                    <td>{{ number_format($order->total, 2) }} ر.س</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">لا توجد طلبات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Users -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>أحدث المستخدمين</span>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentUsers ?? [] as $user)
                        <li class="list-group-item d-flex align-items-center">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar me-3">
                            <div>
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-4">لا يوجد مستخدمين</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
