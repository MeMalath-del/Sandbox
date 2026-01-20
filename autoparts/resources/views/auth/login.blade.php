<x-auth-layout>
    <x-slot name="title">تسجيل الدخول</x-slot>
    
    <div class="auth-card">
        <div class="auth-header">
            <i class="bi bi-gear-wide-connected fs-1 mb-3 d-block"></i>
            <h4 class="mb-1">مرحباً بعودتك!</h4>
            <p class="mb-0 opacity-75">سجل دخولك للمتابعة</p>
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
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني أو رقم الهاتف</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="أدخل البريد الإلكتروني أو رقم الهاتف" value="{{ old('email') }}" required>
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
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">نسيت كلمة المرور؟</a>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-auth mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> تسجيل الدخول
                </button>
            </form>
            
            <div class="divider">أو سجل الدخول باستخدام</div>
            
            <div class="social-login mb-4">
                <a href="#" class="social-btn"><i class="bi bi-google"></i></a>
                <a href="#" class="social-btn"><i class="bi bi-apple"></i></a>
                <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
            </div>
            
            <p class="text-center mb-0">
                ليس لديك حساب؟
                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">إنشاء حساب جديد</a>
            </p>
        </div>
    </div>
    
    <script>
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
