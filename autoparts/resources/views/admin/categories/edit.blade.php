<x-dashboard-layout>
    <x-slot name="title">تعديل التصنيف</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تعديل التصنيف: {{ $category->name }}</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم التصنيف (عربي)</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">اسم التصنيف (إنجليزي)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $category->name_en) }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">التصنيف الأب</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- تصنيف رئيسي --</option>
                            @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الأيقونة (Bootstrap Icons)</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="bi-car-front">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الصورة</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="" class="mt-2" style="height: 60px;">
                    @endif
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $category->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">نشط</label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> تحديث التصنيف
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
