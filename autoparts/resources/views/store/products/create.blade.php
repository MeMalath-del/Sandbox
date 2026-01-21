<x-dashboard-layout>
    <x-slot name="title">إضافة منتج جديد</x-slot>

    <h4 class="fw-bold mb-4">إضافة منتج جديد</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('store.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">اسم المنتج</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                               value="{{ old('sku') }}" required>
                        @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>
                                        — {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">العلامة التجارية</label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">بدون</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">السعر الأساسي</label>
                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">سعر الخصم (اختياري)</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price') }}">
                        @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الكمية في المخزون</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', 0) }}" required>
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">وصف مختصر</label>
                        <textarea name="short_description" rows="2"
                                  class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description') }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">الوصف التفصيلي</label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الوزن (اختياري)</label>
                        <input type="number" step="0.01" name="weight" class="form-control @error('weight') is-invalid @enderror"
                               value="{{ old('weight') }}">
                        @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الأبعاد (اختياري)</label>
                        <input type="text" name="dimensions" class="form-control @error('dimensions') is-invalid @enderror"
                               value="{{ old('dimensions') }}">
                        @error('dimensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">رقم القطعة (اختياري)</label>
                        <input type="text" name="part_number" class="form-control @error('part_number') is-invalid @enderror"
                               value="{{ old('part_number') }}">
                        @error('part_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">رقم OEM (اختياري)</label>
                        <input type="text" name="oem_number" class="form-control @error('oem_number') is-invalid @enderror"
                               value="{{ old('oem_number') }}">
                        @error('oem_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">صور المنتج</label>
                        <input type="file" name="images[]" multiple
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                               required>
                        <small class="text-muted d-block mt-1">يمكنك رفع أكثر من صورة، وستكون أول صورة هي الرئيسية.</small>
                        @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- مواصفات بسيطة اختيارية (حقل واحد مبدئياً) --}}
                    <div class="col-md-6">
                        <label class="form-label">اسم المواصفة (اختياري)</label>
                        <input type="text" name="specs[0][name]" class="form-control" value="{{ old('specs.0.name') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">قيمة المواصفة (اختياري)</label>
                        <input type="text" name="specs[0][value]" class="form-control" value="{{ old('specs.0.value') }}">
                    </div>

                    {{-- توافق السيارة (اختياري) --}}
                    <div class="col-md-4">
                        <label class="form-label">ماركة السيارة (اختياري)</label>
                        <select name="compatibilities[0][make_id]" class="form-select">
                            <option value="">بدون</option>
                            @foreach($carMakes as $make)
                                <option value="{{ $make->id }}" @selected(old('compatibilities.0.make_id') == $make->id)>
                                    {{ $make->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">موديل السيارة (اختياري)</label>
                        <input type="text" name="compatibilities[0][model_id]" class="form-control"
                               value="{{ old('compatibilities.0.model_id') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">سنة من (اختياري)</label>
                        <input type="number" name="compatibilities[0][year_from]" class="form-control"
                               value="{{ old('compatibilities.0.year_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">سنة إلى (اختياري)</label>
                        <input type="number" name="compatibilities[0][year_to]" class="form-control"
                               value="{{ old('compatibilities.0.year_to') }}">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('store.products.index') }}" class="btn btn-outline-secondary me-2">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ المنتج</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>

