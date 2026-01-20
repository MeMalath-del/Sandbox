<x-dashboard-layout>
    <x-slot name="title">إضافة تصنيف</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">إضافة تصنيف جديد</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم التصنيف (عربي)</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم التصنيف (إنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">التصنيف الأب</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- تصنيف رئيسي --</option>
                            @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الأيقونة (Bootstrap Icons)</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="bi-car-front">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الصورة</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                    <label class="form-check-label" for="isActive">نشط</label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> حفظ التصنيف
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
