@extends('layouts.app')

@section('title', 'سياراتي')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-car-front me-2"></i>سياراتي</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
            <i class="bi bi-plus-lg me-2"></i>إضافة سيارة
        </button>
    </div>

    @if($cars->count() > 0)
    <div class="row g-4">
        @foreach($cars as $car)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm {{ $car->is_default ? 'border-primary border-2' : '' }}">
                <div class="card-body">
                    @if($car->is_default)
                    <span class="badge bg-primary mb-2">السيارة الافتراضية</span>
                    @endif
                    
                    <div class="d-flex align-items-center mb-3">
                        @if($car->make->logo)
                        <img src="{{ $car->make->logo_url }}" alt="{{ $car->make->name }}" class="me-3" style="height: 50px;">
                        @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-car-front fs-4 text-muted"></i>
                        </div>
                        @endif
                        <div>
                            <h5 class="mb-0">{{ $car->nickname ?? $car->full_name }}</h5>
                            @if($car->nickname)
                            <small class="text-muted">{{ $car->full_name }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        @if($car->plate_number)
                        <div class="col-6">
                            <small class="text-muted d-block">رقم اللوحة</small>
                            <span>{{ $car->plate_number }}</span>
                        </div>
                        @endif
                        @if($car->color)
                        <div class="col-6">
                            <small class="text-muted d-block">اللون</small>
                            <span>{{ $car->color }}</span>
                        </div>
                        @endif
                        @if($car->mileage)
                        <div class="col-6">
                            <small class="text-muted d-block">المسافة المقطوعة</small>
                            <span>{{ number_format($car->mileage) }} كم</span>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('cars.compatible', $car) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-seam me-1"></i>قطع متوافقة
                        </a>
                        <a href="{{ route('cars.maintenance', $car) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-tools me-1"></i>جدول الصيانة
                        </a>
                        @if(!$car->is_default)
                        <form action="{{ route('cars.setDefault', $car) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-star me-1"></i>جعلها افتراضية
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('cars.destroy', $car) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه السيارة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-car-front fs-1 text-muted"></i>
            <h5 class="mt-3">لم تضف أي سيارة بعد</h5>
            <p class="text-muted">أضف سيارتك للحصول على توصيات قطع الغيار المتوافقة</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
                <i class="bi bi-plus-lg me-2"></i>إضافة سيارة
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Add Car Modal -->
<div class="modal fade" id="addCarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة سيارة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cars.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الشركة المصنعة <span class="text-danger">*</span></label>
                        <select name="make_id" id="makeSelect" class="form-select" required>
                            <option value="">اختر الشركة</option>
                            @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name_ar ?? $make->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الموديل <span class="text-danger">*</span></label>
                        <select name="model_id" id="modelSelect" class="form-select" required disabled>
                            <option value="">اختر الموديل</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">السنة <span class="text-danger">*</span></label>
                        <select name="year" id="yearSelect" class="form-select" required>
                            <option value="">اختر السنة</option>
                            @for($y = date('Y') + 1; $y >= 1980; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الاسم المستعار</label>
                        <input type="text" name="nickname" class="form-control" placeholder="مثال: سيارتي الرئيسية">
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">رقم اللوحة</label>
                            <input type="text" name="plate_number" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">اللون</label>
                            <input type="text" name="color" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المسافة المقطوعة (كم)</label>
                        <input type="number" name="mileage" class="form-control" min="0">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_default" value="1" class="form-check-input" id="isDefault">
                        <label class="form-check-label" for="isDefault">جعلها السيارة الافتراضية</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة السيارة</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('makeSelect').addEventListener('change', function() {
    const makeId = this.value;
    const modelSelect = document.getElementById('modelSelect');
    
    if (!makeId) {
        modelSelect.disabled = true;
        modelSelect.innerHTML = '<option value="">اختر الموديل</option>';
        return;
    }
    
    fetch(`/api/car-makes/${makeId}/models`)
        .then(res => res.json())
        .then(models => {
            modelSelect.disabled = false;
            modelSelect.innerHTML = '<option value="">اختر الموديل</option>';
            models.forEach(model => {
                modelSelect.innerHTML += `<option value="${model.id}">${model.name_ar || model.name}</option>`;
            });
        });
});
</script>
@endpush
@endsection
