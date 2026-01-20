<x-dashboard-layout>
    <x-slot name="title">عرض المستخدم</x-slot>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">تفاصيل المستخدم</h4>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> العودة للمستخدمين
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ $user->status == 'active' ? 'نشط' : ($user->status == 'pending' ? 'معلق' : $user->status) }}
                    </span>
                    <span class="badge bg-secondary">{{ $user->user_type }}</span>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">تغيير الحالة</div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select mb-3">
                            <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>موقوف</option>
                            <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>محظور</option>
                        </select>
                        <button type="submit" class="btn btn-primary w-100">تحديث الحالة</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">المعلومات الأساسية</div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>الاسم:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>البريد الإلكتروني:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>رقم الجوال:</th>
                            <td>{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>نوع الحساب:</th>
                            <td>{{ $user->user_type }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ التسجيل:</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر دخول:</th>
                            <td>{{ $user->last_login_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($user->orders && $user->orders->count() > 0)
            <div class="card">
                <div class="card-header">آخر الطلبات</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->take(5) as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ number_format($order->total, 2) }} ر.س</td>
                                <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
