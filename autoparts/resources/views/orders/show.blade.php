<x-app-layout>
    <x-slot name="title">طلب #{{ $order->order_number }}</x-slot>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">طلب #{{ $order->order_number }}</h2>
                <p class="text-muted mb-0">{{ $order->created_at->format('d/m/Y h:i A') }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-1"></i> العودة للطلبات
            </a>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mb-4">
                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-header">حالة الطلب</div>
                    <div class="card-body">
                        @php
                            $steps = [
                                'pending' => ['icon' => 'hourglass-split', 'label' => 'قيد المراجعة'],
                                'confirmed' => ['icon' => 'check-circle', 'label' => 'تم التأكيد'],
                                'processing' => ['icon' => 'box', 'label' => 'جاري التجهيز'],
                                'shipped' => ['icon' => 'truck', 'label' => 'تم الشحن'],
                                'delivered' => ['icon' => 'check2-all', 'label' => 'تم التوصيل'],
                            ];
                            $currentStep = array_search($order->status, array_keys($steps));
                        @endphp
                        <div class="d-flex justify-content-between">
                            @foreach($steps as $key => $step)
                            @php $stepIndex = array_search($key, array_keys($steps)); @endphp
                            <div class="text-center flex-fill">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle {{ $stepIndex <= $currentStep ? 'bg-primary text-white' : 'bg-light text-muted' }}" style="width: 50px; height: 50px;">
                                    <i class="bi bi-{{ $step['icon'] }}"></i>
                                </div>
                                <p class="small mt-2 mb-0 {{ $stepIndex <= $currentStep ? 'text-primary fw-bold' : 'text-muted' }}">{{ $step['label'] }}</p>
                            </div>
                            @if(!$loop->last)
                            <div class="flex-fill d-flex align-items-center">
                                <div class="flex-grow-1 {{ $stepIndex < $currentStep ? 'bg-primary' : 'bg-light' }}" style="height: 3px;"></div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">المنتجات</div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                        <div class="d-flex gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                            <img src="{{ $item->product->image_url ?? '/images/placeholder.png' }}" alt="" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                <p class="text-muted small mb-1">{{ $item->store->name ?? '' }}</p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ $item->quantity }}x {{ number_format($item->unit_price, 2) }} ر.س</span>
                                    <span class="fw-bold">{{ number_format($item->total, 2) }} ر.س</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Order History -->
                @if($order->statusHistories && $order->statusHistories->count() > 0)
                <div class="card">
                    <div class="card-header">سجل الطلب</div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($order->statusHistories as $history)
                            <li class="{{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">{{ $history->notes ?? $history->status }}</span>
                                    <small class="text-muted">{{ $history->created_at->format('d/m/Y h:i A') }}</small>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">ملخص الطلب</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">المجموع الفرعي</span>
                            <span>{{ number_format($order->subtotal, 2) }} ر.س</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">الشحن</span>
                            <span>{{ number_format($order->shipping_cost, 2) }} ر.س</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>الخصم</span>
                            <span>- {{ number_format($order->discount, 2) }} ر.س</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>الإجمالي</span>
                            <span class="text-primary fs-5">{{ number_format($order->total, 2) }} ر.س</span>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header">عنوان التوصيل</div>
                    <div class="card-body">
                        <p class="mb-1 fw-bold">{{ $order->shipping_name }}</p>
                        <p class="mb-1">{{ $order->shipping_address }}</p>
                        <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_region }}</p>
                        <p class="mb-0">{{ $order->shipping_phone }}</p>
                    </div>
                </div>
                
                <!-- Payment Info -->
                <div class="card">
                    <div class="card-header">معلومات الدفع</div>
                    <div class="card-body">
                        <p class="mb-1">
                            <span class="text-muted">طريقة الدفع:</span>
                            @switch($order->payment_method)
                                @case('cod')
                                    الدفع عند الاستلام
                                    @break
                                @case('wallet')
                                    المحفظة
                                    @break
                                @case('card')
                                    بطاقة ائتمان
                                    @break
                                @default
                                    {{ $order->payment_method }}
                            @endswitch
                        </p>
                        <p class="mb-0">
                            <span class="text-muted">حالة الدفع:</span>
                            @if($order->payment_status == 'paid')
                            <span class="badge bg-success">مدفوع</span>
                            @else
                            <span class="badge bg-warning">قيد الانتظار</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
