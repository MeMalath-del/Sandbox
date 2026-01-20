<x-dashboard-layout>
    <x-slot name="title">إدارة المتاجر</x-slot>
    
    <h4 class="fw-bold mb-4">إدارة المتاجر</h4>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المتجر</th>
                            <th>المالك</th>
                            <th>المنتجات</th>
                            <th>التقييم</th>
                            <th>الحالة</th>
                            <th>موثق</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                        <tr>
                            <td>{{ $store->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $store->logo_url }}" alt="" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    {{ $store->name }}
                                </div>
                            </td>
                            <td>{{ $store->user->name ?? '-' }}</td>
                            <td>{{ $store->products_count }}</td>
                            <td>
                                <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                {{ number_format($store->rating, 1) }}
                            </td>
                            <td>
                                @if($store->status == 'active')
                                <span class="badge bg-success">نشط</span>
                                @else
                                <span class="badge bg-secondary">{{ $store->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($store->is_verified)
                                <span class="badge bg-success"><i class="bi bi-check"></i></span>
                                @else
                                <span class="badge bg-secondary"><i class="bi bi-x"></i></span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.stores.show', $store) }}" class="btn btn-sm btn-outline-primary">
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $stores->links() }}
        </div>
    </div>
</x-dashboard-layout>
