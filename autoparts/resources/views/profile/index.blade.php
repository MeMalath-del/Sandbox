<x-app-layout>
    <x-slot name="title">الملف الشخصي</x-slot>
    
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>
                        <span class="badge bg-primary">{{ auth()->user()->user_type_label }}</span>
                    </div>
                </div>
                
                <div class="list-group mt-3">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-person me-2"></i> الملف الشخصي
                    </a>
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-bag me-2"></i> طلباتي
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-heart me-2"></i> المفضلة
                    </a>
                    <a href="{{ route('profile.addresses') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-geo-alt me-2"></i> العناوين
                    </a>
                    <a href="{{ route('profile.wallet') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-wallet2 me-2"></i> المحفظة
                    </a>
                    <a href="{{ route('profile.settings') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i> الإعدادات
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">تعديل الملف الشخصي</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الاسم الأول</label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', auth()->user()->first_name) }}" required>
                                    @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الاسم الأخير</label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', auth()->user()->last_name) }}" required>
                                    @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">رقم الجوال</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">الصورة الشخصية</label>
                                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr class="my-4">
                            <h6 class="mb-3">تغيير كلمة المرور (اختياري)</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">كلمة المرور الحالية</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> حفظ التغييرات
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
