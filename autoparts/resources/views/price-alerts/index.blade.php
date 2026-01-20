@extends('layouts.app')

@section('title', 'تنبيهات الأسعار')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4"><i class="bi bi-bell me-2"></i>تنبيهات الأسعار</h1>

    @if($alerts->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @foreach($alerts as $alert)
            <div class="d-flex align-items-center p-3 border-bottom">
                <img src="{{ $alert->product->primaryImage?->url ?? '/images/placeholder.jpg' }}" class="rounded me-3" width="80" height="80" style="object-fit: cover;">
                
                <div class="flex-grow-1">
                    <h6 class="mb-1">
                        <a href="{{ route('products.show', $alert->product) }}" class="text-dark text-decoration-none">
                            {{ $alert->product->name }}
                        </a>
                    </h6>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">
                            السعر الحالي: <strong>{{ number_format($alert->product->sale_price ?? $alert->product->price, 2) }} ر.س</strong>
                        </span>
                        <span class="text-muted small">
                            السعر المستهدف: <strong class="text-success">{{ number_format($alert->target_price, 2) }} ر.س</strong>
                        </span>
                    </div>
                    
                    @if($alert->status === 'triggered')
                    <span class="badge bg-success mt-2">
                        <i class="bi bi-check-circle me-1"></i>تم تفعيل التنبيه!
                    </span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <form action="{{ route('price-alerts.toggle', $alert) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $alert->status === 'active' ? 'outline-warning' : 'outline-success' }}">
                            <i class="bi bi-{{ $alert->status === 'active' ? 'pause' : 'play' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('price-alerts.destroy', $alert) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    {{ $alerts->links() }}
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-bell fs-1 text-muted"></i>
            <h5 class="mt-3">لا توجد تنبيهات أسعار</h5>
            <p class="text-muted">أضف تنبيهات للمنتجات التي ترغب في متابعة أسعارها</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">تصفح المنتجات</a>
        </div>
    </div>
    @endif
</div>
@endsection
