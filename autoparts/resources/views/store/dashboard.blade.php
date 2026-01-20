<x-dashboard-layout>
    <x-slot name="title">لوحة تحكم المتجر</x-slot>
    <x-slot name="header">لوحة تحكم المتجر</x-slot>
    
    <x-slot name="sidebar">
        <div class="nav-section-title">الرئيسية</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('store.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">المنتجات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.products') }}">
                    <i class="bi bi-box-seam"></i> المنتجات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.products.create') }}">
                    <i class="bi bi-plus-circle"></i> إضافة منتج
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.inventory') }}">
                    <i class="bi bi-boxes"></i> المخزون
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">الطلبات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.orders') }}">
                    <i class="bi bi-bag"></i> الطلبات
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">التقارير</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.reports') }}">
                    <i class="bi bi-graph-up"></i> التقارير
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.customers') }}">
                    <i class="bi bi-people"></i> العملاء
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.reviews') }}">
                    <i class="bi bi-star"></i> التقييمات
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">الإعدادات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('store.settings') }}">
                    <i class="bi bi-gear"></i> إعدادات المتجر
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
                        <p class="stat-label mb-1">طلبات اليوم</p>
                        <h3 class="stat-value mb-0">{{ $todayOrders ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-bag"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">مبيعات اليوم</p>
                        <h3 class="stat-value mb-0">{{ number_format($todaySales ?? 0, 2) }} <small class="fs-6">ر.س</small></h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">طلبات معلقة</p>
                        <h3 class="stat-value mb-0">{{ $pendingOrders ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">إجمالي المنتجات</p>
                        <h3 class="stat-value mb-0">{{ $totalProducts ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-box-seam"></i>
                    </div>
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
                    <a href="{{ route('store.orders') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>العميل</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td><a href="{{ route('store.orders.show', $order) }}">#{{ $order->order_number }}</a></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ number_format($order->total, 2) }} ر.س</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ __("orders.status.{$order->status}") }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">لا توجد طلبات حتى الآن</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>منتجات منخفضة المخزون</span>
                    <a href="{{ route('store.inventory') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lowStockProducts ?? [] as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ Str::limit($product->name, 30) }}</h6>
                                <small class="text-muted">{{ $product->sku }}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">{{ $product->quantity }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-check-circle fs-3 d-block mb-2 text-success"></i>
                            جميع المنتجات متوفرة
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
