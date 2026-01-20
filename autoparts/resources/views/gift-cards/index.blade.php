@extends('layouts.app')

@section('title', 'بطاقات الهدايا')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3"><i class="bi bi-gift text-danger"></i> بطاقات الهدايا</h1>
        <p class="text-muted lead">أهدِ أحبائك بطاقة هدية واجعلهم يختارون ما يناسبهم</p>
    </div>

    <div class="row g-5">
        <!-- Purchase -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">شراء بطاقة هدية</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('gift-cards.purchase') }}" method="POST">
                        @csrf
                        
                        <!-- Amount -->
                        <div class="mb-4">
                            <label class="form-label">اختر القيمة</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($denominations as $amount)
                                <input type="radio" class="btn-check" name="amount" value="{{ $amount }}" id="amount{{ $amount }}" {{ $loop->index === 2 ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="amount{{ $amount }}">{{ number_format($amount) }} ر.س</label>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <input type="number" name="custom_amount" class="form-control" placeholder="أو أدخل مبلغ مخصص" min="50" max="5000">
                            </div>
                        </div>

                        <!-- Recipient -->
                        <div class="mb-4">
                            <h6>معلومات المستلم (اختياري)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">اسم المستلم</label>
                                    <input type="text" name="recipient_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="recipient_email" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label class="form-label">رسالة (اختياري)</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="أضف رسالة شخصية للمستلم"></textarea>
                        </div>

                        <!-- Send Date -->
                        <div class="mb-4">
                            <label class="form-label">تاريخ الإرسال</label>
                            <input type="date" name="send_date" class="form-control" min="{{ date('Y-m-d') }}">
                            <small class="text-muted">اتركه فارغاً للإرسال فوراً</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-cart-plus me-2"></i>شراء البطاقة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Redeem & My Cards -->
        <div class="col-lg-5">
            <!-- Redeem -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">استخدام بطاقة هدية</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('gift-cards.redeem') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="code" class="form-control" placeholder="XXXX-XXXX-XXXX" style="text-transform: uppercase;">
                            <button type="submit" class="btn btn-success">استخدام</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Check Balance -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">التحقق من الرصيد</h5>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" id="checkCode" class="form-control" placeholder="XXXX-XXXX-XXXX" style="text-transform: uppercase;">
                        <button type="button" class="btn btn-outline-primary" onclick="checkBalance()">تحقق</button>
                    </div>
                    <div id="balanceResult" class="d-none"></div>
                </div>
            </div>

            <!-- My Cards -->
            @auth
            @if($myGiftCards->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">بطاقاتي</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($myGiftCards->take(5) as $card)
                    <a href="{{ route('gift-cards.show', $card) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between">
                            <div>
                                <code>{{ $card->formatted_code }}</code>
                                <small class="text-muted d-block">{{ $card->created_at->format('Y/m/d') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold">{{ number_format($card->balance, 2) }} ر.س</span>
                                <small class="text-muted d-block">من {{ number_format($card->amount, 2) }} ر.س</small>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>

@push('scripts')
<script>
function checkBalance() {
    const code = document.getElementById('checkCode').value;
    if (!code) return;
    
    fetch('{{ route('gift-cards.check-balance') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
        const result = document.getElementById('balanceResult');
        result.classList.remove('d-none');
        
        if (data.success) {
            result.innerHTML = `
                <div class="alert alert-success">
                    <div class="d-flex justify-content-between">
                        <span>الرصيد المتاح</span>
                        <strong>${data.balance.toFixed(2)} ر.س</strong>
                    </div>
                    ${data.expires_at ? `<small class="text-muted">صالحة حتى: ${data.expires_at}</small>` : ''}
                </div>
            `;
        } else {
            result.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    });
}
</script>
@endpush
@endsection
