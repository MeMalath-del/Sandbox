@extends('layouts.app')

@section('title', 'تذكرة دعم جديدة')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-headset me-2"></i>تذكرة دعم جديدة</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">الموضوع <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">الفئة <span class="text-danger">*</span></label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">اختر الفئة</option>
                                    @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الأولوية <span class="text-danger">*</span></label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>منخفضة</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>متوسطة</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>عالية</option>
                                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>عاجلة</option>
                                </select>
                                @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($orders->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">الطلب المتعلق (اختياري)</label>
                            <select name="order_id" class="form-select">
                                <option value="">اختر طلب</option>
                                @foreach($orders as $order)
                                <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                    #{{ $order->order_number }} - {{ $order->created_at->format('Y/m/d') }} - {{ number_format($order->total, 2) }} ر.س
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">الرسالة <span class="text-danger">*</span></label>
                            <textarea name="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">المرفقات (اختياري)</label>
                            <input type="file" name="attachments[]" class="form-control" multiple accept="image/*,.pdf,.doc,.docx">
                            <small class="text-muted">يمكنك إرفاق صور أو مستندات (حد أقصى 5 ملفات)</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>إرسال التذكرة
                            </button>
                            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
