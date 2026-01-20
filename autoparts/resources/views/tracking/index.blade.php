@extends('layouts.app')

@section('title', 'تتبع الطلب')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-truck fs-1 text-primary"></i>
                        <h2 class="mt-3">تتبع طلبك</h2>
                        <p class="text-muted">أدخل رقم الطلب لمعرفة حالته</p>
                    </div>

                    <form action="{{ route('tracking.track') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">رقم الطلب</label>
                            <input type="text" name="order_number" class="form-control form-control-lg" placeholder="مثال: ORD-123456" required>
                        </div>

                        @guest
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني (للتحقق)</label>
                            <input type="email" name="email" class="form-control" placeholder="أدخل بريدك الإلكتروني">
                        </div>
                        @endguest

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-search me-2"></i>تتبع الطلب
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
