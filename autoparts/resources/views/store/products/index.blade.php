<x-dashboard-layout>
    <x-slot name="title">منتجاتي</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">منتجاتي</h4>
        <a href="{{ route('store.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> إضافة منتج
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>التصنيف</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->image_url }}" alt="" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <small class="text-muted d-block">{{ $product->sku }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ number_format($product->price, 2) }} ر.س</td>
                            <td>
                                @if($product->quantity <= 5)
                                <span class="text-danger">{{ $product->quantity }}</span>
                                @else
                                {{ $product->quantity }}
                                @endif
                            </td>
                            <td>
                                @if($product->status == 'active')
                                <span class="badge bg-success">نشط</span>
                                @elseif($product->status == 'pending')
                                <span class="badge bg-warning">قيد المراجعة</span>
                                @else
                                <span class="badge bg-secondary">{{ $product->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('store.products.edit', $product) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('store.products.destroy', $product) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
            @else
            <div class="text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                <h5>لا توجد منتجات</h5>
                <p class="text-muted">أضف منتجك الأول لبدء البيع</p>
                <a href="{{ route('store.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> إضافة منتج
                </a>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
