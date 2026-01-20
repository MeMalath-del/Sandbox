<x-dashboard-layout>
    <x-slot name="title">تقرير المستخدمين</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تقرير المستخدمين</h4>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للتقارير
        </a>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">إجمالي المستخدمين</h6>
                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">مستخدمين جدد (هذا الشهر)</h6>
                    <h3 class="mb-0">{{ $stats['this_month'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">المشترين</h6>
                    <h3 class="mb-0">{{ $stats['buyers'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50">البائعين</h6>
                    <h3 class="mb-0">{{ $stats['sellers'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">التسجيلات الأخيرة</div>
        <div class="card-body">
            @if(isset($users) && $users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد</th>
                            <th>النوع</th>
                            <th>تاريخ التسجيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-secondary">{{ $user->user_type }}</span></td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
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
