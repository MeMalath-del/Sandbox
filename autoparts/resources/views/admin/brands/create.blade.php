<x-dashboard-layout>
    <x-slot name="title">إضافة ماركة</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">إضافة ماركة جديدة</h4>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم الماركة</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">بلد المنشأ</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الشعار</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                    <label class="form-check-label" for="isActive">نشط</label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> حفظ الماركة
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
