<x-dashboard-layout>
    <x-slot name="title">لوحة تحكم الموصل</x-slot>
    <x-slot name="header">لوحة تحكم الموصل</x-slot>
    
    <x-slot name="sidebar">
        <div class="nav-section-title">الرئيسية</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('driver.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> لوحة التحكم
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">التوصيلات</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('driver.deliveries.available') }}">
                    <i class="bi bi-geo-alt"></i> توصيلات متاحة
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('driver.deliveries') }}">
                    <i class="bi bi-truck"></i> توصيلاتي
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">المالية</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('driver.earnings') }}">
                    <i class="bi bi-wallet2"></i> الأرباح
                </a>
            </li>
        </ul>
        
        <div class="nav-section-title">أخرى</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('driver.ratings') }}">
                    <i class="bi bi-star"></i> التقييمات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('driver.settings') }}">
                    <i class="bi bi-gear"></i> الإعدادات
                </a>
            </li>
        </ul>
    </x-slot>
    
    <!-- Status Toggle -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">حالتك الحالية</h5>
                    <p class="text-muted mb-0">قم بتغيير حالتك لاستقبال أو إيقاف الطلبات</p>
                </div>
                <div class="btn-group" role="group">
                    <form action="{{ route('driver.status.update') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="available">
                        <button type="submit" class="btn {{ ($driver->status ?? '') === 'available' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="bi bi-check-circle me-1"></i> متاح
                        </button>
                    </form>
                    <form action="{{ route('driver.status.update') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="busy">
                        <button type="submit" class="btn {{ ($driver->status ?? '') === 'busy' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="bi bi-pause-circle me-1"></i> مشغول
                        </button>
                    </form>
                    <form action="{{ route('driver.status.update') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="offline">
                        <button type="submit" class="btn {{ ($driver->status ?? '') === 'offline' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            <i class="bi bi-x-circle me-1"></i> غير متاح
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">توصيلات اليوم</p>
                        <h3 class="stat-value mb-0">{{ $todayDeliveries ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">أرباح اليوم</p>
                        <h3 class="stat-value mb-0">{{ number_format($todayEarnings ?? 0, 2) }} <small class="fs-6">ر.س</small></h3>
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
                        <p class="stat-label mb-1">توصيلات متاحة</p>
                        <h3 class="stat-value mb-0">{{ $pendingDeliveries ?? 0 }}</h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="stat-label mb-1">التقييم</p>
                        <h3 class="stat-value mb-0">{{ number_format($driver->rating ?? 0, 1) }} <i class="bi bi-star-fill text-warning fs-5"></i></h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Delivery -->
    @if($activeDelivery ?? false)
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-truck me-2"></i> توصيل نشط
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>طلب #{{ $activeDelivery->order->order_number }}</h5>
                    <p class="mb-1"><i class="bi bi-shop me-2"></i> {{ $activeDelivery->order->store->name }}</p>
                    <p class="mb-1"><i class="bi bi-geo-alt me-2"></i> {{ $activeDelivery->delivery_address }}</p>
                    <p class="mb-0"><i class="bi bi-telephone me-2"></i> {{ $activeDelivery->order->shipping_phone }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('driver.deliveries.show', $activeDelivery) }}" class="btn btn-primary">
                        <i class="bi bi-eye me-1"></i> عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Recent Deliveries -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>أحدث التوصيلات</span>
            <a href="{{ route('driver.deliveries') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>المتجر</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>الربح</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeliveries ?? [] as $delivery)
                        <tr>
                            <td><a href="{{ route('driver.deliveries.show', $delivery) }}">#{{ $delivery->order->order_number }}</a></td>
                            <td>{{ $delivery->order->store->name }}</td>
                            <td>{{ Str::limit($delivery->delivery_address, 30) }}</td>
                            <td>
                                <span class="badge bg-{{ $delivery->status === 'delivered' ? 'success' : 'info' }}">
                                    {{ $delivery->status }}
                                </span>
                            </td>
                            <td>{{ number_format($delivery->driver_earning, 2) }} ر.س</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">لا توجد توصيلات حتى الآن</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
