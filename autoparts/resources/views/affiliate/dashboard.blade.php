@extends('layouts.app')

@section('title', 'برنامج الشراكة')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4"><i class="bi bi-people me-2"></i>برنامج الشراكة</h1>

    @if($affiliate->status === 'pending')
    <div class="alert alert-warning">
        <i class="bi bi-clock me-2"></i>
        طلبك قيد المراجعة. سيتم تفعيل حسابك خلال 24-48 ساعة.
    </div>
    @elseif($affiliate->status === 'rejected')
    <div class="alert alert-danger">
        <i class="bi bi-x-circle me-2"></i>
        تم رفض طلبك للانضمام لبرنامج الشراكة.
    </div>
    @endif

    <div class="row g-4 mb-5">
        <!-- Stats Cards -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-mouse text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted">إجمالي النقرات</small>
                            <h4 class="mb-0">{{ number_format($stats['total_clicks']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="bi bi-bag-check text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted">الطلبات المكتملة</small>
                            <h4 class="mb-0">{{ number_format($stats['total_orders']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-wallet2 text-warning"></i>
                        </div>
                        <div>
                            <small class="text-muted">إجمالي الأرباح</small>
                            <h4 class="mb-0">{{ number_format($stats['total_earnings'], 2) }} ر.س</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="bi bi-hourglass-split text-info"></i>
                        </div>
                        <div>
                            <small class="text-muted">أرباح معلقة</small>
                            <h4 class="mb-0">{{ number_format($stats['pending_earnings'], 2) }} ر.س</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Affiliate Link -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>رابط الإحالة الخاص بك</h5>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="{{ route('affiliate.link', $affiliate->code) }}" id="affiliateLink" readonly>
                        <button class="btn btn-primary" type="button" onclick="copyLink()">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">كود الإحالة</label>
                        <input type="text" class="form-control" value="{{ $affiliate->code }}" readonly>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        نسبة العمولة الحالية: <strong>{{ $affiliate->commission_rate }}%</strong> من قيمة كل طلب
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Custom Link -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>إنشاء رابط مخصص</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">رابط الصفحة</label>
                        <input type="url" class="form-control" id="customUrl" placeholder="أدخل رابط أي صفحة في الموقع">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="generateLink()">إنشاء الرابط</button>
                    
                    <div id="generatedLink" class="mt-3 d-none">
                        <label class="form-label">الرابط المخصص</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="generatedUrl" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyGenerated()">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Commissions -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>آخر العمولات</h5>
            <a href="{{ route('affiliate.withdrawals') }}" class="btn btn-sm btn-outline-primary">طلب سحب</a>
        </div>
        <div class="card-body p-0">
            @if($recentCommissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>الطلب</th>
                            <th>قيمة الطلب</th>
                            <th>العمولة</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCommissions as $commission)
                        <tr>
                            <td>#{{ $commission->order->order_number ?? '-' }}</td>
                            <td>{{ number_format($commission->order->total ?? 0, 2) }} ر.س</td>
                            <td class="text-success fw-medium">+{{ number_format($commission->amount, 2) }} ر.س</td>
                            <td>
                                <span class="badge bg-{{ $commission->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $commission->status === 'paid' ? 'مدفوع' : 'معلق' }}
                                </span>
                            </td>
                            <td>{{ $commission->created_at->format('Y/m/d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">لا توجد عمولات بعد</p>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyLink() {
    navigator.clipboard.writeText(document.getElementById('affiliateLink').value);
    alert('تم نسخ الرابط!');
}

function copyGenerated() {
    navigator.clipboard.writeText(document.getElementById('generatedUrl').value);
    alert('تم نسخ الرابط!');
}

function generateLink() {
    const url = document.getElementById('customUrl').value;
    if (!url) return;
    
    fetch('{{ route('affiliate.generate-link') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ url: url })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('generatedUrl').value = data.url;
        document.getElementById('generatedLink').classList.remove('d-none');
    });
}
</script>
@endpush
@endsection
