<x-dashboard-layout>
    <x-slot name="title">إدارة المستخدمين</x-slot>
    
    <h4 class="fw-bold mb-4">إدارة المستخدمين</h4>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الجوال</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>التسجيل</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $user->user_type }}</span>
                            </td>
                            <td>
                                @if($user->status == 'active')
                                <span class="badge bg-success">نشط</span>
                                @elseif($user->status == 'pending')
                                <span class="badge bg-warning">معلق</span>
                                @else
                                <span class="badge bg-danger">{{ $user->status }}</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
</x-dashboard-layout>
