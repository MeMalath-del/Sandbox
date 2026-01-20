@extends('layouts.app')

@section('title', 'طلبات الإرجاع')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4"><i class="bi bi-arrow-return-left me-2"></i>طلبات الإرجاع</h1>

    @if($returns->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>رقم الطلب</th>
                        <th>الطلب الأصلي</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr>
                        <td>#{{ $return->id }}</td>
                        <td>
                            <a href="{{ route('orders.show', $return->order) }}">
                                #{{ $return->order->order_number }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-{{ $return->type === 'refund' ? 'warning' : 'info' }}">
                                {{ $return->type === 'refund' ? 'استرداد' : 'استبدال' }}
                            </span>
                        </td>
                        <td>{{ number_format($return->total_amount, 2) }} ر.س</td>
                        <td>
                            @php
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'info',
                                'rejected' => 'danger',
                                'received' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'secondary',
                            ];
                            $statusLabels = [
                                'pending' => 'قيد المراجعة',
                                'approved' => 'موافق عليه',
                                'rejected' => 'مرفوض',
                                'received' => 'تم الاستلام',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي',
                            ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                {{ $statusLabels[$return->status] ?? $return->status }}
                            </span>
                        </td>
                        <td>{{ $return->created_at->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('returns.show', $return) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $returns->links() }}
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-arrow-return-left fs-1 text-muted"></i>
            <p class="text-muted mt-3">لا توجد طلبات إرجاع</p>
            <a href="{{ route('orders.index') }}" class="btn btn-primary">عرض الطلبات</a>
        </div>
    </div>
    @endif
</div>
@endsection
