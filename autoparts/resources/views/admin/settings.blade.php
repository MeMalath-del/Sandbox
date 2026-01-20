<x-dashboard-layout>
    <x-slot name="title">إعدادات النظام</x-slot>
    
    <h4 class="fw-bold mb-4">إعدادات النظام</h4>
    
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">الإعدادات العامة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">اسم الموقع</label>
                            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'AutoParts Hub' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="site_email" class="form-control" value="{{ $settings['site_email'] ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="site_phone" class="form-control" value="{{ $settings['site_phone'] ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea name="site_address" class="form-control" rows="2">{{ $settings['site_address'] ?? '' }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> حفظ
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">إعدادات الشحن والتوصيل</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">رسوم التوصيل الافتراضية</label>
                            <div class="input-group">
                                <input type="number" name="default_shipping_fee" class="form-control" value="{{ $settings['default_shipping_fee'] ?? 25 }}">
                                <span class="input-group-text">ر.س</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">حد الشحن المجاني</label>
                            <div class="input-group">
                                <input type="number" name="free_shipping_threshold" class="form-control" value="{{ $settings['free_shipping_threshold'] ?? 200 }}">
                                <span class="input-group-text">ر.س</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">نسبة الضريبة</label>
                            <div class="input-group">
                                <input type="number" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] ?? 15 }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> حفظ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
