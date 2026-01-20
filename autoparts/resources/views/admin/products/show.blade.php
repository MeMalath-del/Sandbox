<x-dashboard-layout>
    <x-slot name="title">عرض المنتج</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تفاصيل المنتج</h4>
        <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للمنتجات
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $product->name }}</h4>
                            <p class="text-muted">{{ $product->sku }}</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-{{ $product->status == 'active' ? 'success' : ($product->status == 'pending' ? 'warning' : 'secondary') }} fs-6">
                                    {{ $product->status == 'active' ? 'نشط' : ($product->status == 'pending' ? 'قيد المراجعة' : $product->status) }}
                                </span>
                            </div>
                            
                            <table class="table table-sm">
                                <tr>
                                    <th>المتجر:</th>
                                    <td>{{ $product->store->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>التصنيف:</th>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>الماركة:</th>
                                    <td>{{ $product->brand->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>السعر:</th>
                                    <td>{{ number_format($product->price, 2) }} ر.س</td>
                                </tr>
                                <tr>
                                    <th>الكمية:</th>
                                    <td>{{ $product->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>{{ $product->condition == 'new' ? 'جديد' : ($product->condition == 'used' ? 'مستعمل' : 'مجدد') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($product->description)
                    <hr>
                    <h6>الوصف:</h6>
                    <p>{{ $product->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">إجراءات</div>
                <div class="card-body">
                    @if($product->status == 'pending')
                    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i> قبول المنتج
                        </button>
                    </form>
                    <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="reason" value="لا يتوافق مع الشروط">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-lg me-1"></i> رفض المنتج
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">إحصائيات</div>
                <div class="card-body">
                    <p><strong>المشاهدات:</strong> {{ $product->views_count }}</p>
                    <p><strong>المبيعات:</strong> {{ $product->sales_count }}</p>
                    <p><strong>التقييم:</strong> {{ number_format($product->rating, 1) }} ({{ $product->rating_count }} تقييم)</p>
                    <p><strong>المفضلة:</strong> {{ $product->wishlist_count }}</p>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
