<x-dashboard-layout>
    <x-slot name="title">طلبات المتجر</x-slot>
    
    <h4 class="fw-bold mb-4">طلبات المتجر</h4>
    
    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>المنتجات</th>
                            <th>الإجمالي</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('store.orders.show', $order) }}" class="text-primary fw-bold">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->items->count() }} منتج</td>
                            <td>{{ number_format($order->total, 2) }} ر.س</td>
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
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('store.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    التفاصيل
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
            @else
            <div class="text-center py-5">
                <i class="bi bi-bag fs-1 text-muted d-block mb-3"></i>
                <h5>لا توجد طلبات</h5>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
