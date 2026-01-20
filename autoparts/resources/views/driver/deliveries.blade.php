<x-dashboard-layout>
    <x-slot name="title">التوصيلات</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">توصيلاتي</h4>
        <a href="{{ route('driver.deliveries.available') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> طلبات متاحة
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            @if($deliveries->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم التوصيل</th>
                            <th>من</th>
                            <th>إلى</th>
                            <th>المسافة</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                        <tr>
                            <td>#{{ $delivery->id }}</td>
                            <td>{{ $delivery->pickup_address ?? '-' }}</td>
                            <td>{{ $delivery->delivery_address ?? '-' }}</td>
                            <td>{{ $delivery->distance ?? '-' }} كم</td>
                            <td>{{ number_format($delivery->delivery_fee ?? 0, 2) }} ر.س</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'accepted' => 'info',
                                        'picked_up' => 'primary',
                                        'in_transit' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">
                                    {{ $delivery->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('driver.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                    التفاصيل
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-truck fs-1 text-muted d-block mb-3"></i>
                <h5>لا توجد توصيلات</h5>
                <p class="text-muted">تصفح الطلبات المتاحة للتوصيل</p>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
