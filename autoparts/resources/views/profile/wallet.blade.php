<x-app-layout>
    <x-slot name="title">المحفظة</x-slot>
    
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="list-group">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-person me-2"></i> الملف الشخصي
                    </a>
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-bag me-2"></i> طلباتي
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-heart me-2"></i> المفضلة
                    </a>
                    <a href="{{ route('profile.addresses') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-geo-alt me-2"></i> العناوين
                    </a>
                    <a href="{{ route('profile.wallet') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-wallet2 me-2"></i> المحفظة
                    </a>
                    <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i> الإعدادات
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Balance Card -->
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="text-white-50 mb-1">الرصيد المتاح</h6>
                                <h2 class="mb-0">{{ number_format($wallet->balance ?? 0, 2) }} ر.س</h2>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addFundsModal">
                                    <i class="bi bi-plus-circle me-1"></i> إضافة رصيد
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loyalty Points -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1"><i class="bi bi-star-fill text-warning me-2"></i>نقاط الولاء</h6>
                                <p class="text-muted mb-0">لديك {{ $loyaltyPoints ?? 0 }} نقطة (تعادل {{ number_format(($loyaltyPoints ?? 0) / 10, 2) }} ر.س)</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                @if(($loyaltyPoints ?? 0) >= 100)
                                <button class="btn btn-outline-primary btn-sm">تحويل للمحفظة</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Transactions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">سجل المعاملات</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($transactions && $transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>الوصف</th>
                                        <th>المبلغ</th>
                                        <th>الرصيد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="text-muted">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->type == 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} ر.س
                                        </td>
                                        <td>{{ number_format($transaction->balance_after, 2) }} ر.س</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-receipt fs-1 text-muted d-block mb-3"></i>
                            <h5>لا توجد معاملات</h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Funds Modal -->
    <div class="modal fade" id="addFundsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة رصيد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.wallet.addFunds') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">المبلغ</label>
                            <div class="input-group">
                                <input type="number" name="amount" class="form-control" min="10" step="10" required>
                                <span class="input-group-text">ر.س</span>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col"><button type="button" class="btn btn-outline-secondary w-100" onclick="document.querySelector('[name=amount]').value = 50">50</button></div>
                            <div class="col"><button type="button" class="btn btn-outline-secondary w-100" onclick="document.querySelector('[name=amount]').value = 100">100</button></div>
                            <div class="col"><button type="button" class="btn btn-outline-secondary w-100" onclick="document.querySelector('[name=amount]').value = 200">200</button></div>
                            <div class="col"><button type="button" class="btn btn-outline-secondary w-100" onclick="document.querySelector('[name=amount]').value = 500">500</button></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">طريقة الدفع</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="card">بطاقة ائتمان/مدى</option>
                                <option value="bank">تحويل بنكي</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة رصيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
