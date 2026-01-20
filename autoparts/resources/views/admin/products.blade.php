<x-dashboard-layout>
    <x-slot name="title">إدارة المنتجات</x-slot>
    
    <h4 class="fw-bold mb-4">إدارة المنتجات</h4>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>المتجر</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->image_url }}" alt="" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <strong>{{ Str::limit($product->name, 40) }}</strong>
                                        <small class="text-muted d-block">{{ $product->sku }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->store->name ?? '-' }}</td>
                            <td>{{ number_format($product->price, 2) }} ر.س</td>
                            <td>{{ $product->quantity }}</td>
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
                                    @if($product->status == 'pending')
                                    <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>
</x-dashboard-layout>
