<x-dashboard-layout>
    <x-slot name="title">تقرير المبيعات</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تقرير المبيعات</h4>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للتقارير
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            @if($sales->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>عدد الطلبات</th>
                            <th>إجمالي المبيعات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                            <td>{{ $sale->count }}</td>
                            <td class="text-success fw-bold">{{ number_format($sale->total, 2) }} ر.س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $sales->links() }}
            @else
            <div class="text-center py-5">
                <i class="bi bi-graph-up fs-1 text-muted d-block mb-3"></i>
                <h5>لا توجد بيانات مبيعات</h5>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
