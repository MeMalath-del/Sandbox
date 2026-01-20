<x-dashboard-layout>
    <x-slot name="title">إعدادات المتجر</x-slot>
    
    <h4 class="fw-bold mb-4">إعدادات المتجر</h4>
    
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('store.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم المتجر</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $store->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $store->email) }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">رقم الجوال</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $store->phone) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">واتساب</label>
                        <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $store->whatsapp) }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $store->address) }}">
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">المدينة</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $store->city) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">المنطقة</label>
                        <input type="text" name="region" class="form-control" value="{{ old('region', $store->region) }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">وصف المتجر</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $store->description) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">شعار المتجر</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if($store->logo)
                    <img src="{{ $store->logo_url }}" alt="Logo" class="mt-2" style="height: 60px;">
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> حفظ التغييرات
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
