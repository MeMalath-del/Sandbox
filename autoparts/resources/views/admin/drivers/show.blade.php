<x-dashboard-layout>
    <x-slot name="title">عرض الموصل</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تفاصيل الموصل</h4>
        <a href="{{ route('admin.drivers') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للموصلين
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $driver->user->avatar_url ?? '/images/default-avatar.png' }}" alt="{{ $driver->user->name ?? '-' }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5>{{ $driver->user->name ?? '-' }}</h5>
                    <p class="text-muted">{{ $driver->user->email ?? '-' }}</p>
                    <span class="badge bg-{{ $driver->status == 'available' ? 'success' : ($driver->status == 'pending' ? 'warning' : 'secondary') }}">
                        {{ $driver->status == 'available' ? 'متاح' : ($driver->status == 'pending' ? 'قيد المراجعة' : $driver->status) }}
                    </span>
                    @if($driver->is_verified)
                    <span class="badge bg-success"><i class="bi bi-patch-check-fill"></i></span>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">إجراءات</div>
                <div class="card-body">
                    @if($driver->status == 'pending')
                    <form action="{{ route('admin.drivers.approve', $driver) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i> قبول الموصل
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">معلومات الموصل</div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>رقم الرخصة:</th>
                            <td>{{ $driver->license_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>انتهاء الرخصة:</th>
                            <td>{{ $driver->license_expiry?->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>التقييم:</th>
                            <td>
                                <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                {{ number_format($driver->rating ?? 0, 1) }} ({{ $driver->rating_count ?? 0 }} تقييم)
                            </td>
                        </tr>
                        <tr>
                            <th>التوصيلات المكتملة:</th>
                            <td>{{ $driver->completed_deliveries ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>إجمالي الأرباح:</th>
                            <td>{{ number_format($driver->total_earnings ?? 0, 2) }} ر.س</td>
                        </tr>
                        <tr>
                            <th>تاريخ التسجيل:</th>
                            <td>{{ $driver->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($driver->vehicles && $driver->vehicles->count() > 0)
            <div class="card">
                <div class="card-header">المركبات</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>النوع</th>
                                <th>الموديل</th>
                                <th>رقم اللوحة</th>
                                <th>اللون</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driver->vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->type }}</td>
                                <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                                <td>{{ $vehicle->plate_number }}</td>
                                <td>{{ $vehicle->color ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
