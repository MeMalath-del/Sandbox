<x-dashboard-layout>
    <x-slot name="title">الإعدادات</x-slot>
    
    <h4 class="fw-bold mb-4">إعدادات السائق</h4>
    
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات السائق</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('driver.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">رقم رخصة القيادة</label>
                            <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $driver->license_number ?? '') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">تاريخ انتهاء الرخصة</label>
                            <input type="date" name="license_expiry" class="form-control" value="{{ old('license_expiry', $driver->license_expiry ?? '') }}">
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
                    <h5 class="mb-0">حالة التوفر</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('driver.status.update') }}" method="POST">
                        @csrf
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_available" id="isAvailable" {{ ($driver->is_available ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isAvailable">
                                متاح للتوصيل
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="accept_new_orders" id="acceptOrders" {{ ($driver->accept_new_orders ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="acceptOrders">
                                قبول طلبات جديدة
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> تحديث الحالة
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">معلومات المركبة</h5>
                </div>
                <div class="card-body">
                    @if($vehicle ?? false)
                    <p><strong>النوع:</strong> {{ $vehicle->type }}</p>
                    <p><strong>الموديل:</strong> {{ $vehicle->make }} {{ $vehicle->model }}</p>
                    <p><strong>رقم اللوحة:</strong> {{ $vehicle->plate_number }}</p>
                    @else
                    <p class="text-muted">لم تتم إضافة مركبة بعد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
