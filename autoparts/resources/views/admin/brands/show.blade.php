<x-dashboard-layout>
    <x-slot name="title">عرض الماركة</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ $brand->name }}</h4>
        <div>
            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> تعديل
            </a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-1"></i> العودة
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    @if($brand->logo)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="max-height: 100px;">
                    </div>
                    @endif
                    
                    <table class="table">
                        <tr>
                            <th>الاسم:</th>
                            <td>{{ $brand->name }}</td>
                        </tr>
                        <tr>
                            <th>بلد المنشأ:</th>
                            <td>{{ $brand->country ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الحالة:</th>
                            <td>
                                @if($brand->is_active)
                                <span class="badge bg-success">نشط</span>
                                @else
                                <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>عدد المنتجات:</th>
                            <td>{{ $brand->products->count() }}</td>
                        </tr>
                    </table>
                    
                    @if($brand->description)
                    <h6>الوصف:</h6>
                    <p>{{ $brand->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
