<x-dashboard-layout>
    <x-slot name="title">عرض التصنيف</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ $category->name }}</h4>
        <div>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> تعديل
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right me-1"></i> العودة
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>الاسم (عربي):</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>الاسم (إنجليزي):</th>
                            <td>{{ $category->name_en ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>التصنيف الأب:</th>
                            <td>{{ $category->parent->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الأيقونة:</th>
                            <td>
                                @if($category->icon)
                                <i class="bi {{ $category->icon }} fs-4"></i> {{ $category->icon }}
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>الحالة:</th>
                            <td>
                                @if($category->is_active)
                                <span class="badge bg-success">نشط</span>
                                @else
                                <span class="badge bg-secondary">غير نشط</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>عدد المنتجات:</th>
                            <td>{{ $category->products->count() }}</td>
                        </tr>
                    </table>
                    
                    @if($category->description)
                    <h6>الوصف:</h6>
                    <p>{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
