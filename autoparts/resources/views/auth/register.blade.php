<x-auth-layout>
    <x-slot name="title">إنشاء حساب جديد</x-slot>
    
    <div class="auth-card">
        <div class="auth-header">
            <i class="bi bi-person-plus fs-1 mb-3 d-block"></i>
            <h4 class="mb-1">إنشاء حساب جديد</h4>
            <p class="mb-0 opacity-75">انضم إلينا اليوم</p>
        </div>
        
        <div class="auth-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- User Type Selection -->
            <div class="mb-4">
                <label class="form-label fw-bold">اختر نوع الحساب</label>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="user-type-card" data-type="buyer_individual" onclick="selectUserType(this)">
                            <i class="bi bi-person d-block"></i>
                            <div class="fw-bold">مشتري فرد</div>
                            <small class="text-muted">للاستخدام الشخصي</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="user-type-card" data-type="buyer_company" onclick="selectUserType(this)">
                            <i class="bi bi-building d-block"></i>
                            <div class="fw-bold">مشتري شركة</div>
                            <small class="text-muted">ورش ومؤسسات</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="user-type-card" data-type="store_individual" onclick="selectUserType(this)">
                            <i class="bi bi-shop d-block"></i>
                            <div class="fw-bold">متجر فرد</div>
                            <small class="text-muted">بائع قطع غيار</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="user-type-card" data-type="driver_individual" onclick="selectUserType(this)">
                            <i class="bi bi-truck d-block"></i>
                            <div class="fw-bold">موصل</div>
                            <small class="text-muted">سائق توصيل</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                <input type="hidden" name="user_type" id="userTypeInput" value="buyer_individual">
                
                <div class="mb-3">
                    <label class="form-label">الاسم الكامل</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="أدخل اسمك الكامل" value="{{ old('name') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="أدخل البريد الإلكتروني" value="{{ old('email') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">رقم الهاتف</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="tel" name="phone" class="form-control" placeholder="05xxxxxxxx" value="{{ old('phone') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="أدخل كلمة المرور" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">8 أحرف على الأقل</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="أعد إدخال كلمة المرور" required>
                    </div>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        أوافق على <a href="#" class="text-primary">الشروط والأحكام</a> و<a href="#" class="text-primary">سياسة الخصوصية</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-auth mb-3">
                    <i class="bi bi-person-plus me-2"></i> إنشاء الحساب
                </button>
            </form>
            
            <p class="text-center mb-0">
                لديك حساب بالفعل؟
                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">تسجيل الدخول</a>
            </p>
        </div>
    </div>
    
    <script>
        function selectUserType(element) {
            document.querySelectorAll('.user-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            element.classList.add('selected');
            document.getElementById('userTypeInput').value = element.dataset.type;
        }
        
        // Select first by default
        document.querySelector('.user-type-card').classList.add('selected');
        
        function togglePassword(btn) {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</x-auth-layout>
