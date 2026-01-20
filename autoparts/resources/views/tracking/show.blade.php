@extends('layouts.app')

@section('title', 'تتبع الطلب #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Order Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-1">طلب #{{ $order->order_number }}</h4>
                            <small class="text-muted">{{ $order->created_at->format('Y/m/d H:i') }}</small>
                        </div>
                        <span class="badge bg-primary fs-6">
                            @switch($order->status)
                                @case('pending') قيد الانتظار @break
                                @case('confirmed') تم التأكيد @break
                                @case('processing') جاري التجهيز @break
                                @case('shipped') تم الشحن @break
                                @case('out_for_delivery') في الطريق @break
                                @case('delivered') تم التوصيل @break
                                @case('cancelled') ملغي @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>حالة الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($timeline as $step)
                        <div class="timeline-item {{ $step['completed'] ? 'completed' : '' }} {{ $step['current'] ? 'current' : '' }}">
                            <div class="timeline-icon">
                                @if($step['completed'])
                                <i class="bi bi-check-lg text-white"></i>
                                @else
                                <i class="bi {{ $step['icon'] }}"></i>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $step['label'] }}</h6>
                                @if($step['date'])
                                <small class="text-muted">{{ $step['date']->format('Y/m/d H:i') }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            @if($order->delivery && in_array($order->status, ['shipped', 'out_for_delivery']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>معلومات التوصيل</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($order->delivery->driver->user->name ?? 'Driver') }}&size=50" class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-0">{{ $order->delivery->driver->user->name ?? 'السائق' }}</h6>
                                    <small class="text-muted">سائق التوصيل</small>
                                </div>
                            </div>
                            @if($order->delivery->driver?->user?->phone)
                            <a href="tel:{{ $order->delivery->driver->user->phone }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-telephone me-1"></i>اتصل بالسائق
                            </a>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($order->delivery->estimated_arrival_at)
                            <p class="mb-1"><strong>الوصول المتوقع:</strong></p>
                            <p class="text-primary fs-5">{{ $order->delivery->estimated_arrival_at->format('H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    @if(in_array($order->delivery->status, ['picked_up', 'in_transit']))
                    <hr>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#liveTrackingModal">
                            <i class="bi bi-geo-alt-fill me-2"></i>تتبع مباشر
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-bag me-2"></i>محتويات الطلب</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{ $item->product->primaryImage?->url ?? '/images/placeholder.jpg' }}" class="rounded" width="60" height="60" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $item->product->name }}</div>
                                        <small class="text-muted">الكمية: {{ $item->quantity }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($item->unit_price * $item->quantity, 2) }} ر.س</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">الإجمالي</span>
                            <span class="fw-bold text-primary fs-5">{{ number_format($order->total, 2) }} ر.س</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    right: 20px;
    width: 2px;
    background: #e9ecef;
}
.timeline-item {
    position: relative;
    padding-right: 60px;
    padding-bottom: 30px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-icon {
    position: absolute;
    right: 10px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #6c757d;
}
.timeline-item.completed .timeline-icon {
    background: #198754;
    color: white;
}
.timeline-item.current .timeline-icon {
    background: #0d6efd;
    color: white;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
}
.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}
.timeline-item.current .timeline-content {
    background: #e7f1ff;
    border: 1px solid #0d6efd;
}
</style>
@endsection
