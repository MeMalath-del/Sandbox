@extends('layouts.app')

@section('title', 'تذكرة #' . $ticket->ticket_number)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Ticket Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $ticket->subject }}</h5>
                        <small class="text-muted">{{ $ticket->ticket_number }}</small>
                    </div>
                    <span class="badge bg-{{ $ticket->status_color }} fs-6">
                        @switch($ticket->status)
                            @case('open') مفتوحة @break
                            @case('in_progress') قيد المعالجة @break
                            @case('customer_reply') بانتظار الرد @break
                            @case('resolved') تم الحل @break
                            @case('closed') مغلقة @break
                        @endswitch
                    </span>
                </div>
                <div class="card-body">
                    <!-- Messages -->
                    <div class="ticket-messages">
                        @foreach($ticket->replies as $reply)
                        <div class="d-flex mb-4 {{ $reply->is_staff ? '' : 'flex-row-reverse' }}">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle {{ $reply->is_staff ? 'bg-primary' : 'bg-secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi {{ $reply->is_staff ? 'bi-headset' : 'bi-person' }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 {{ $reply->is_staff ? 'ms-3' : 'me-3' }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong>{{ $reply->is_staff ? 'فريق الدعم' : $reply->user->name }}</strong>
                                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="p-3 rounded {{ $reply->is_staff ? 'bg-light' : 'bg-primary bg-opacity-10' }}">
                                    {!! nl2br(e($reply->message)) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($ticket->status !== 'closed')
                    <!-- Reply Form -->
                    <hr>
                    <form action="{{ route('tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">إضافة رد</label>
                            <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>إرسال الرد
                        </button>
                    </form>
                    @endif

                    @if($ticket->status === 'closed' && !$ticket->rating)
                    <!-- Rating Form -->
                    <hr>
                    <form action="{{ route('tickets.rate', $ticket) }}" method="POST">
                        @csrf
                        <h6>قيّم تجربتك مع الدعم</h6>
                        <div class="mb-3">
                            <div class="rating-stars">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="d-none" required>
                                <label for="star{{ $i }}" class="fs-3 text-warning" style="cursor: pointer;">
                                    <i class="bi bi-star"></i>
                                </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="feedback" class="form-control" rows="2" placeholder="أخبرنا عن تجربتك (اختياري)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">إرسال التقييم</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Ticket Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">تفاصيل التذكرة</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">الفئة</small>
                        <span>
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
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">الأولوية</small>
                        <span class="badge bg-{{ $ticket->priority_color }}">
                            {{ $ticket->priority === 'urgent' ? 'عاجل' : ($ticket->priority === 'high' ? 'عالي' : ($ticket->priority === 'medium' ? 'متوسط' : 'منخفض')) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">تاريخ الإنشاء</small>
                        <span>{{ $ticket->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    @if($ticket->order)
                    <div class="mb-3">
                        <small class="text-muted d-block">الطلب المرتبط</small>
                        <a href="{{ route('orders.show', $ticket->order) }}">#{{ $ticket->order->order_number }}</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($ticket->status !== 'closed')
                    <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-x-circle me-2"></i>إغلاق التذكرة
                        </button>
                    </form>
                    @else
                    <form action="{{ route('tickets.reopen', $ticket) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-arrow-repeat me-2"></i>إعادة فتح التذكرة
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('tickets.index') }}" class="btn btn-light w-100">
                        <i class="bi bi-arrow-right me-2"></i>العودة للتذاكر
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}
.rating-stars label:hover i,
.rating-stars label:hover ~ label i,
.rating-stars input:checked ~ label i {
    content: "\F586";
}
.rating-stars label:hover i:before,
.rating-stars label:hover ~ label i:before,
.rating-stars input:checked ~ label i:before {
    content: "\F586";
}
</style>
@endsection
