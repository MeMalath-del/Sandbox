<x-app-layout>
    <x-slot name="title">العناوين</x-slot>
    
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
                    <a href="{{ route('profile.addresses') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-geo-alt me-2"></i> العناوين
                    </a>
                    <a href="{{ route('profile.wallet') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-wallet2 me-2"></i> المحفظة
                    </a>
                    <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i> الإعدادات
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">العناوين المحفوظة</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="bi bi-plus-lg me-1"></i> إضافة عنوان
                    </button>
                </div>
                
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if($addresses && $addresses->count() > 0)
                <div class="row g-4">
                    @foreach($addresses as $address)
                    <div class="col-md-6">
                        <div class="card h-100 {{ $address->is_default ? 'border-primary' : '' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $address->label }}</h6>
                                    @if($address->is_default)
                                    <span class="badge bg-primary">الافتراضي</span>
                                    @endif
                                </div>
                                <p class="text-muted mb-2">
                                    {{ $address->address_line_1 }}<br>
                                    @if($address->address_line_2){{ $address->address_line_2 }}<br>@endif
                                    {{ $address->city }}, {{ $address->region }}<br>
                                    {{ $address->postal_code }}
                                </p>
                                <p class="mb-2">
                                    <i class="bi bi-telephone me-1"></i> {{ $address->phone }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary">تعديل</button>
                                    @if(!$address->is_default)
                                    <form action="{{ route('profile.addresses.setDefault', $address) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">تعيين كافتراضي</button>
                                    </form>
                                    <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-geo-alt fs-1 text-muted d-block mb-3"></i>
                    <h5>لا توجد عناوين محفوظة</h5>
                    <p class="text-muted">أضف عنوانك الأول لتسهيل عملية الشراء</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عنوان جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">تسمية العنوان</label>
                            <input type="text" name="label" class="form-control" placeholder="مثال: المنزل، العمل" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="address_line_1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تفاصيل إضافية (اختياري)</label>
                            <input type="text" name="address_line_2" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المدينة</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المنطقة</label>
                                <input type="text" name="region" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الرمز البريدي</label>
                                <input type="text" name="postal_code" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الجوال</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
                            <label class="form-check-label" for="isDefault">تعيين كعنوان افتراضي</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ العنوان</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
