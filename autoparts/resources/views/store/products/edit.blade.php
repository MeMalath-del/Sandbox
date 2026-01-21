<x-dashboard-layout>
    <x-slot name="title">تعديل المنتج</x-slot>

    <h4 class="fw-bold mb-4">تعديل المنتج</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('store.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">اسم المنتج</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SKU</label>
                        <input type="text" class="form-control" value="{{ $product->sku }}" disabled>
                        <small class="text-muted">لا يمكن تعديل SKU بعد إنشاء المنتج.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">السعر الأساسي</label>
                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">سعر الخصم (اختياري)</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price', $product->sale_price) }}">
                        @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الكمية في المخزون</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', $product->quantity) }}" required>
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">وصف مختصر</label>
                        <textarea name="short_description" rows="2"
                                  class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">الوصف التفصيلي</label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الوزن (اختياري)</label>
                        <input type="number" step="0.01" name="weight" class="form-control @error('weight') is-invalid @enderror"
                               value="{{ old('weight', $product->weight) }}">
                        @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">الأبعاد (اختياري)</label>
                        <input type="text" name="dimensions" class="form-control @error('dimensions') is-invalid @enderror"
                               value="{{ old('dimensions', $product->dimensions) }}">
                        @error('dimensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">رقم القطعة (اختياري)</label>
                        <input type="text" name="part_number" class="form-control @error('part_number') is-invalid @enderror"
                               value="{{ old('part_number', $product->part_number) }}">
                        @error('part_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">رقم OEM (اختياري)</label>
                        <input type="text" name="oem_number" class="form-control @error('oem_number') is-invalid @enderror"
                               value="{{ old('oem_number', $product->oem_number) }}">
                        @error('oem_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label d-block">الصور الحالية</label>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($product->images as $image)
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt=""
                                         style="width: 80px; height: 80px; object-fit: cover;" class="rounded">
                                </div>
                            @endforeach
                            @if($product->images->isEmpty())
                                <span class="text-muted">لا توجد صور مضافة بعد.</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">إضافة صور جديدة (اختياري)</label>
                        <input type="file" name="images[]" multiple
                               class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                        <small class="text-muted d-block mt-1">يمكنك إضافة المزيد من الصور، ولن يتم حذف الصور الحالية تلقائياً.</small>
                        @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('store.products.index') }}" class="btn btn-outline-secondary me-2">إلغاء</a>
                    <button type="submit" class="btn btn-primary">تحديث المنتج</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>

