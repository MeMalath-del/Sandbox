<x-dashboard-layout>
    <x-slot name="title">الأرباح</x-slot>
    
    <h4 class="fw-bold mb-4">أرباحي</h4>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">أرباح اليوم</h6>
                    <h3 class="mb-0">{{ number_format($todayEarnings ?? 0, 2) }} ر.س</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">أرباح هذا الأسبوع</h6>
                    <h3 class="mb-0">{{ number_format($weekEarnings ?? 0, 2) }} ر.س</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">إجمالي الأرباح</h6>
                    <h3 class="mb-0">{{ number_format($totalEarnings ?? 0, 2) }} ر.س</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">سجل الأرباح</h5>
        </div>
        <div class="card-body">
            @if(isset($earnings) && $earnings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الوصف</th>
                            <th>المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($earnings as $earning)
                        <tr>
                            <td>{{ $earning->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $earning->description ?? 'توصيل طلب' }}</td>
                            <td class="text-success">+{{ number_format($earning->amount, 2) }} ر.س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-muted">لا توجد أرباح مسجلة</p>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
