<x-app-layout>
    <x-slot name="title">طلباتي</x-slot>
    
    <div class="container py-5">
        <h2 class="fw-bold mb-4">طلباتي</h2>
        
        @if($orders && $orders->count() > 0)
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>المنتجات</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="text-primary fw-bold">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->items_count }} منتج</td>
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
                                            'confirmed' => 'تم التأكيد',
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
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        التفاصيل
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
            <h5>لا توجد طلبات</h5>
            <p class="text-muted mb-3">لم تقم بأي طلب بعد</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-grid me-1"></i> تصفح المنتجات
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
