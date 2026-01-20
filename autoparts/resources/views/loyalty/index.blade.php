@extends('layouts.app')

@section('title', 'برنامج الولاء')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold mb-3"><i class="bi bi-gem text-warning"></i> برنامج الولاء</h1>
            <p class="text-muted">اكسب نقاط مع كل عملية شراء واستبدلها بخصومات رائعة</p>
        </div>
    </div>

    <!-- Points Summary -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 bg-gradient text-white p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <span class="text-white-50 mb-2">رصيدك الحالي</span>
                                <h1 class="display-3 fw-bold mb-3">{{ number_format($loyaltyPoints->balance) }}</h1>
                                <span class="fs-5">نقطة</span>
                                <div class="mt-3">
                                    <small class="text-white-50">= {{ number_format($loyaltyPoints->balance / 100, 2) }} ر.س</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                    <i class="bi {{ $currentTier['icon'] }} text-warning fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted">مستواك الحالي</small>
                                    <h4 class="mb-0">{{ $currentTier['name'] }}</h4>
                                </div>
                            </div>
                            
                            @if($nextTier)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between text-muted small mb-2">
                                    <span>{{ $currentTier['name'] }}</span>
                                    <span>{{ $nextTier['name'] }}</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    @php
                                        $progress = min(100, (($loyaltyPoints->total_earned - $currentTier['min']) / ($nextTier['min'] - $currentTier['min'])) * 100);
                                    @endphp
                                    <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format($nextTier['min'] - $loyaltyPoints->total_earned) }} نقطة للمستوى التالي</small>
                            </div>
                            @endif

                            <form action="{{ route('loyalty.redeem') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="number" name="points" class="form-control" placeholder="عدد النقاط" min="100" max="{{ $loyaltyPoints->balance }}">
                                    <button type="submit" class="btn btn-warning">استبدال</button>
                                </div>
                                <small class="text-muted">الحد الأدنى للاستبدال: 100 نقطة</small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiers -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4 text-center">مستويات العضوية</h3>
            <div class="row g-4">
                @foreach($tiers as $tier)
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm {{ $currentTier['name'] === $tier['name'] ? 'border-warning border-2' : '' }}">
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: {{ $currentTier['name'] === $tier['name'] ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : '#f8f9fa' }}">
                                <i class="bi {{ $tier['icon'] }} fs-1 {{ $currentTier['name'] === $tier['name'] ? 'text-white' : 'text-muted' }}"></i>
                            </div>
                            <h5 class="fw-bold">{{ $tier['name'] }}</h5>
                            <p class="text-muted small">{{ number_format($tier['min']) }}+ نقطة</p>
                            @if($tier['discount'] > 0)
                            <span class="badge bg-success">{{ $tier['discount'] }}% خصم</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- How it works -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4 text-center">كيف يعمل البرنامج؟</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-bag-check fs-3 text-primary"></i>
                            </div>
                            <h5>تسوق</h5>
                            <p class="text-muted mb-0">اشترِ من متجرنا واكسب نقطة واحدة مقابل كل ريال</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-success bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-coin fs-3 text-success"></i>
                            </div>
                            <h5>اجمع</h5>
                            <p class="text-muted mb-0">اجمع النقاط وارتقِ لمستويات أعلى للحصول على مزايا أكثر</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-warning bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-gift fs-3 text-warning"></i>
                            </div>
                            <h5>استبدل</h5>
                            <p class="text-muted mb-0">استبدل نقاطك برصيد في المحفظة (100 نقطة = 1 ر.س)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>سجل النقاط</h5>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الوصف</th>
                                    <th>النقاط</th>
                                    <th>الرصيد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        <span class="{{ $transaction->points > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($transaction->balance_after) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $transactions->links() }}
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
@endsection
