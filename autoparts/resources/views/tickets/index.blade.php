@extends('layouts.app')

@section('title', 'تذاكر الدعم')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-headset me-2"></i>تذاكر الدعم</h1>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>تذكرة جديدة
        </a>
    </div>

    @if($tickets->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>رقم التذكرة</th>
                        <th>الموضوع</th>
                        <th>الفئة</th>
                        <th>الأولوية</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td><code>{{ $ticket->ticket_number }}</code></td>
                        <td>{{ Str::limit($ticket->subject, 40) }}</td>
                        <td>
                            @php
                            $categories = [
                                'order' => 'مشكلة في طلب',
                                'product' => 'استفسار عن منتج',
                                'payment' => 'مشكلة في الدفع',
                                'delivery' => 'مشكلة في التوصيل',
                                'return' => 'إرجاع أو استبدال',
                                'account' => 'مشكلة في الحساب',
                                'suggestion' => 'اقتراح',
                                'other' => 'أخرى',
                            ];
                            @endphp
                            {{ $categories[$ticket->category] ?? $ticket->category }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $ticket->priority_color }}">
                                {{ $ticket->priority === 'urgent' ? 'عاجل' : ($ticket->priority === 'high' ? 'عالي' : ($ticket->priority === 'medium' ? 'متوسط' : 'منخفض')) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $ticket->status_color }}">
                                @switch($ticket->status)
                                    @case('open') مفتوحة @break
                                    @case('in_progress') قيد المعالجة @break
                                    @case('customer_reply') بانتظار الرد @break
                                    @case('resolved') تم الحل @break
                                    @case('closed') مغلقة @break
                                @endswitch
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                عرض
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $tickets->links() }}
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-headset fs-1 text-muted"></i>
            <p class="text-muted mt-3">لا توجد تذاكر دعم</p>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">إنشاء تذكرة جديدة</a>
        </div>
    </div>
    @endif
</div>
@endsection
