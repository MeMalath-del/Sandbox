<x-dashboard-layout>
    <x-slot name="title">إدارة الطلبات</x-slot>
    
    <h4 class="fw-bold mb-4">إدارة الطلبات</h4>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>المتجر</th>
                            <th>الإجمالي</th>
                            <th>الحالة</th>
                            <th>الدفع</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-bold">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->store->name ?? '-' }}</td>
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
                            <td>
                                @if($order->payment_status == 'paid')
                                <span class="badge bg-success">مدفوع</span>
                                @else
                                <span class="badge bg-warning">{{ $order->payment_status }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    التفاصيل
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</x-dashboard-layout>
