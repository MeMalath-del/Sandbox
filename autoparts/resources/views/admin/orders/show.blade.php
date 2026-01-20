<x-dashboard-layout>
    <x-slot name="title">تفاصيل الطلب</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">طلب #{{ $order->order_number }}</h4>
        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للطلبات
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">المنتجات</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->image_url ?? '/images/placeholder.png' }}" alt="" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        {{ $item->product_name }}
                                    </div>
                                </td>
                                <td>{{ number_format($item->unit_price, 2) }} ر.س</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total, 2) }} ر.س</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($order->statusHistory && $order->statusHistory->count() > 0)
            <div class="card">
                <div class="card-header">سجل الطلب</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($order->statusHistory as $history)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $history->notes ?? $history->status }}</span>
                            <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">ملخص الطلب</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>الحالة:</th>
                            <td><span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">{{ $order->status }}</span></td>
                        </tr>
                        <tr>
                            <th>الدفع:</th>
                            <td><span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">{{ $order->payment_status == 'paid' ? 'مدفوع' : 'قيد الانتظار' }}</span></td>
                        </tr>
                        <tr>
                            <th>طريقة الدفع:</th>
                            <td>{{ $order->payment_method }}</td>
                        </tr>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>المجموع الفرعي:</span>
                        <span>{{ number_format($order->subtotal, 2) }} ر.س</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>الشحن:</span>
                        <span>{{ number_format($order->shipping_cost, 2) }} ر.س</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between text-success">
                        <span>الخصم:</span>
                        <span>- {{ number_format($order->discount, 2) }} ر.س</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>الإجمالي:</span>
                        <span>{{ number_format($order->total, 2) }} ر.س</span>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">معلومات العميل</div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->user->name ?? '-' }}</strong></p>
                    <p class="mb-1 text-muted">{{ $order->user->email ?? '-' }}</p>
                    <p class="mb-0">{{ $order->user->phone ?? '-' }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">عنوان التوصيل</div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_region }}</p>
                    <p class="mb-0">{{ $order->shipping_phone }}</p>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
