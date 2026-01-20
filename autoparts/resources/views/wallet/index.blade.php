@extends('layouts.app')

@section('title', 'المحفظة')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Wallet Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <small class="text-white-50">الرصيد المتاح</small>
                            <h1 class="display-4 fw-bold mb-0">{{ number_format($wallet->balance, 2) }}</h1>
                            <span>ر.س</span>
                        </div>
                        <i class="bi bi-wallet2 fs-1 text-white-50"></i>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light flex-grow-1" data-bs-toggle="modal" data-bs-target="#depositModal">
                            <i class="bi bi-plus-circle me-2"></i>إيداع
                        </button>
                        <button type="button" class="btn btn-outline-light flex-grow-1" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                            <i class="bi bi-bank me-2"></i>سحب
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="mb-3">إجراءات سريعة</h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary text-start" data-bs-toggle="modal" data-bs-target="#transferModal">
                            <i class="bi bi-arrow-left-right me-2"></i>تحويل لمستخدم آخر
                        </button>
                        <a href="{{ route('gift-cards.index') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-gift me-2"></i>شراء بطاقة هدية
                        </a>
                        <a href="{{ route('loyalty.index') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-gem me-2"></i>تحويل نقاط الولاء
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>سجل المعاملات</h5>
                    <a href="{{ route('wallet.transactions') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($transactions as $transaction)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-{{ $transaction->type_color }} bg-opacity-10 p-2 me-3">
                                    <i class="bi {{ $transaction->type_icon }} text-{{ $transaction->type_color }}"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $transaction->description }}</div>
                                    <small class="text-muted">{{ $transaction->created_at->format('Y/m/d H:i') }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-{{ $transaction->type_color }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} ر.س
                                </span>
                                @if($transaction->reference)
                                <br><small class="text-muted">{{ $transaction->reference }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">لا توجد معاملات بعد</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إيداع في المحفظة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('wallet.deposit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control form-control-lg" min="10" max="10000" step="0.01" required>
                            <span class="input-group-text">ر.س</span>
                        </div>
                        <small class="text-muted">الحد الأدنى 10 ر.س - الحد الأقصى 10,000 ر.س</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">طريقة الدفع</label>
                        <div class="d-grid gap-2">
                            <div class="form-check border rounded p-3">
                                <input type="radio" name="payment_method" value="card" class="form-check-input" id="methodCard" checked>
                                <label class="form-check-label w-100" for="methodCard">
                                    <i class="bi bi-credit-card me-2"></i>بطاقة ائتمان / مدى
                                </label>
                            </div>
                            <div class="form-check border rounded p-3">
                                <input type="radio" name="payment_method" value="bank_transfer" class="form-check-input" id="methodBank">
                                <label class="form-check-label w-100" for="methodBank">
                                    <i class="bi bi-bank me-2"></i>تحويل بنكي
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إيداع</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سحب من المحفظة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('wallet.withdraw') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control form-control-lg" min="50" max="{{ $wallet->balance }}" step="0.01" required>
                            <span class="input-group-text">ر.س</span>
                        </div>
                        <small class="text-muted">الحد الأدنى 50 ر.س - المتاح {{ number_format($wallet->balance, 2) }} ر.س</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">اسم البنك <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رقم الحساب / IBAN <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">اسم صاحب الحساب <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder" class="form-control" required>
                    </div>

                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-2"></i>
                        سيتم معالجة طلبك خلال 3-5 أيام عمل
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">طلب السحب</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحويل لمستخدم آخر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('wallet.transfer') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني للمستلم <span class="text-danger">*</span></label>
                        <input type="email" name="recipient_email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control" min="1" max="{{ $wallet->balance }}" step="0.01" required>
                            <span class="input-group-text">ر.س</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحويل</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
