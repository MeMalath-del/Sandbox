<x-dashboard-layout>
    <x-slot name="title">لوحة التحكم</x-slot>
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">مرحباً، {{ auth()->user()->name }}</h4>
            <p class="text-muted mb-0">إليك ملخص نشاط المتجر اليوم</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary">
                <i class="bi bi-download me-1"></i> تصدير التقرير
            </button>
            <button class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> إضافة منتج
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">إيرادات اليوم</span>
                    <h3 class="stat-value">{{ number_format($stats['today_sales'] ?? 0, 2) }} <small>ر.س</small></h3>
                    <span class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 12.5% من الأمس
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">طلبات اليوم</span>
                    <h3 class="stat-value">{{ $stats['today_orders'] ?? 0 }}</h3>
                    <span class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 8% من الأمس
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">إجمالي المستخدمين</span>
                    <h3 class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                    <span class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 24 جديد اليوم
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">إجمالي المنتجات</span>
                    <h3 class="stat-value">{{ number_format($stats['total_products'] ?? 0) }}</h3>
                    <span class="stat-change">
                        {{ $stats['pending_products'] ?? 0 }} بانتظار المراجعة
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i> إحصائيات المبيعات</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary active">أسبوع</button>
                        <button class="btn btn-outline-secondary">شهر</button>
                        <button class="btn btn-outline-secondary">سنة</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i> توزيع الطلبات</h5>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart" height="260"></canvas>
                    <div class="chart-legend mt-3">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #10b981;"></span>
                            <span>مكتملة</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #f59e0b;"></span>
                            <span>قيد التنفيذ</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #ef4444;"></span>
                            <span>ملغية</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Second Row -->
    <div class="row g-4 mb-4">
        <!-- Pending Actions -->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-exclamation-circle me-2 text-warning"></i> إجراءات معلقة</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.stores', ['status' => 'pending']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-shop text-primary me-2"></i>
                                متاجر بانتظار الموافقة
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $stats['pending_stores'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.drivers', ['status' => 'pending']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-truck text-success me-2"></i>
                                موصلين بانتظار الموافقة
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $stats['pending_drivers'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.products', ['status' => 'pending']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-box-seam text-warning me-2"></i>
                                منتجات بانتظار المراجعة
                            </div>
                            <span class="badge bg-warning rounded-pill">{{ $stats['pending_products'] ?? 0 }}</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-chat-dots text-info me-2"></i>
                                رسائل دعم جديدة
                            </div>
                            <span class="badge bg-info rounded-pill">5</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> آخر الطلبات</h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>العميل</th>
                                    <th>المتجر</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-primary">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->user->name ?? 'زائر' }}</td>
                                    <td>{{ $order->store->name ?? '-' }}</td>
                                    <td class="fw-bold">{{ number_format($order->total, 2) }} ر.س</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'processing' => 'primary',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusNames = [
                                                'pending' => 'قيد المراجعة',
                                                'confirmed' => 'مؤكد',
                                                'processing' => 'جاري التجهيز',
                                                'shipped' => 'تم الشحن',
                                                'delivered' => 'تم التوصيل',
                                                'cancelled' => 'ملغي',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $statusNames[$order->status] ?? $order->status }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $order->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">لا توجد طلبات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Third Row -->
    <div class="row g-4">
        <!-- Top Products -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-trophy me-2"></i> المنتجات الأكثر مبيعاً</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                            $topProducts = \App\Models\Product::orderByDesc('sales_count')->limit(5)->get();
                        @endphp
                        @foreach($topProducts as $product)
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <span class="rank-badge">{{ $loop->iteration }}</span>
                            <img src="{{ $product->image_url }}" alt="" class="product-thumb">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ Str::limit($product->name, 30) }}</h6>
                                <small class="text-muted">{{ $product->store->name ?? '-' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold d-block">{{ $product->sales_count }} مبيعة</span>
                                <small class="text-success">{{ number_format($product->price * $product->sales_count, 2) }} ر.س</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- New Users -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i> المستخدمين الجدد</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentUsers as $user)
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="" class="user-avatar">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                                    {{ $user->status == 'active' ? 'نشط' : 'معلق' }}
                                </span>
                                <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100%;
            opacity: 0.1;
        }
        
        .stat-card-primary { border-right: 4px solid #1a56db; }
        .stat-card-primary::before { background: linear-gradient(135deg, #1a56db, #3b82f6); }
        .stat-card-primary .stat-icon { background: linear-gradient(135deg, #1a56db, #3b82f6); }
        
        .stat-card-success { border-right: 4px solid #10b981; }
        .stat-card-success::before { background: linear-gradient(135deg, #10b981, #34d399); }
        .stat-card-success .stat-icon { background: linear-gradient(135deg, #10b981, #34d399); }
        
        .stat-card-warning { border-right: 4px solid #f59e0b; }
        .stat-card-warning::before { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stat-card-warning .stat-icon { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        
        .stat-card-info { border-right: 4px solid #06b6d4; }
        .stat-card-info::before { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
        .stat-card-info .stat-icon { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .stat-value {
            font-weight: 700;
            margin: 5px 0;
        }
        
        .stat-value small {
            font-size: 0.9rem;
            font-weight: 400;
        }
        
        .stat-change {
            font-size: 0.85rem;
            color: #6b7280;
        }
        
        .stat-change.positive {
            color: #10b981;
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }
        
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }
        
        .rank-badge {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #6b7280;
        }
        
        .product-thumb {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
                datasets: [{
                    label: 'المبيعات',
                    data: [3200, 4500, 3800, 5200, 4800, 6100, 5500],
                    borderColor: '#1a56db',
                    backgroundColor: 'rgba(26, 86, 219, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                }, {
                    label: 'الطلبات',
                    data: [12, 18, 15, 22, 19, 25, 21],
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    borderWidth: 3,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        rtl: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'right',
                        ticks: {
                            callback: value => value + ' ر.س'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'left',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            callback: value => value + ' طلب'
                        }
                    }
                }
            }
        });
        
        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'doughnut',
            data: {
                labels: ['مكتملة', 'قيد التنفيذ', 'ملغية'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                cutout: '70%',
            }
        });
    </script>
</x-dashboard-layout>
