<x-app-layout>
    <x-slot name="title">الإعدادات</x-slot>
    
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
                    <a href="{{ route('profile.wallet') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-wallet2 me-2"></i> المحفظة
                    </a>
                    <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-gear me-2"></i> الإعدادات
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <!-- Notifications Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">إعدادات الإشعارات</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" {{ auth()->user()->settings['email_notifications'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailNotifications">
                                    إشعارات البريد الإلكتروني
                                    <small class="text-muted d-block">استلام إشعارات عبر البريد الإلكتروني</small>
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotifications" {{ auth()->user()->settings['sms_notifications'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="smsNotifications">
                                    إشعارات الرسائل النصية
                                    <small class="text-muted d-block">استلام إشعارات عبر SMS</small>
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="order_updates" id="orderUpdates" {{ auth()->user()->settings['order_updates'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="orderUpdates">
                                    تحديثات الطلبات
                                    <small class="text-muted d-block">إشعارات تتبع حالة الطلبات</small>
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="promotions" id="promotions" {{ auth()->user()->settings['promotions'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="promotions">
                                    العروض والتخفيضات
                                    <small class="text-muted d-block">إشعارات العروض الجديدة والتخفيضات</small>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                        </form>
                    </div>
                </div>
                
                <!-- Privacy Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">الخصوصية</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="profilePublic" {{ auth()->user()->settings['profile_public'] ?? false ? 'checked' : '' }}>
                            <label class="form-check-label" for="profilePublic">
                                ملف شخصي عام
                                <small class="text-muted d-block">السماح للآخرين بمشاهدة ملفك الشخصي</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Danger Zone -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">منطقة الخطر</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">هذه الإجراءات لا يمكن التراجع عنها</p>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash me-1"></i> حذف الحساب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">حذف الحساب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-danger mb-3">تحذير: سيتم حذف حسابك نهائياً ولن تتمكن من استعادته.</p>
                        <div class="mb-3">
                            <label class="form-label">أدخل كلمة المرور للتأكيد</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">حذف الحساب نهائياً</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
