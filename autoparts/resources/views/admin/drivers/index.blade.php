<x-dashboard-layout>
    <x-slot name="title">إدارة الموصلين</x-slot>
    
    <h4 class="fw-bold mb-4">إدارة الموصلين</h4>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الموصل</th>
                            <th>رقم الجوال</th>
                            <th>التقييم</th>
                            <th>التوصيلات</th>
                            <th>الحالة</th>
                            <th>موثق</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $driver)
                        <tr>
                            <td>{{ $driver->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $driver->user->avatar_url ?? '/images/default-avatar.png' }}" alt="" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    {{ $driver->user->name ?? '-' }}
                                </div>
                            </td>
                            <td>{{ $driver->user->phone ?? '-' }}</td>
                            <td>
                                <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                                {{ number_format($driver->rating ?? 0, 1) }}
                            </td>
                            <td>{{ $driver->completed_deliveries ?? 0 }}</td>
                            <td>
                                @if($driver->status == 'available')
                                <span class="badge bg-success">متاح</span>
                                @elseif($driver->status == 'pending')
                                <span class="badge bg-warning">قيد المراجعة</span>
                                @else
                                <span class="badge bg-secondary">{{ $driver->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($driver->is_verified)
                                <span class="badge bg-success"><i class="bi bi-check"></i></span>
                                @else
                                <span class="badge bg-secondary"><i class="bi bi-x"></i></span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-sm btn-outline-primary">
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $drivers->links() }}
        </div>
    </div>
</x-dashboard-layout>
