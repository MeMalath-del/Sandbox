@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3">الأسئلة الشائعة</h1>
        <p class="text-muted lead">نجيب على أكثر الأسئلة شيوعاً</p>
        
        <div class="row justify-content-center mt-4">
            <div class="col-lg-6">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="faqSearch" class="form-control border-start-0" placeholder="ابحث عن سؤالك...">
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Accordion -->
    @foreach($categories as $category)
    <div class="mb-5">
        <h3 class="mb-4">
            <i class="bi {{ $category->icon ?? 'bi-question-circle' }} me-2 text-primary"></i>
            {{ $category->name }}
        </h3>
        
        <div class="accordion" id="faq-{{ $category->id }}">
            @foreach($category->faqs as $faq)
            <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden faq-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-item-{{ $faq->id }}">
                        {{ $faq->question }}
                    </button>
                </h2>
                <div id="faq-item-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faq-{{ $category->id }}">
                    <div class="accordion-body">
                        <div class="faq-answer">{!! nl2br(e($faq->answer)) !!}</div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">هل كانت هذه الإجابة مفيدة؟</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-success helpful-btn" data-faq="{{ $faq->id }}" data-helpful="1">
                                    <i class="bi bi-hand-thumbs-up"></i> نعم
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger helpful-btn" data-faq="{{ $faq->id }}" data-helpful="0">
                                    <i class="bi bi-hand-thumbs-down"></i> لا
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <!-- Contact -->
    <div class="card border-0 shadow-sm bg-light mt-5">
        <div class="card-body text-center py-5">
            <i class="bi bi-headset fs-1 text-primary mb-3"></i>
            <h4>لم تجد إجابة لسؤالك؟</h4>
            <p class="text-muted mb-4">فريق الدعم جاهز لمساعدتك على مدار الساعة</p>
            <div class="d-flex justify-content-center gap-3">
                @auth
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    <i class="bi bi-envelope me-2"></i>تواصل معنا
                </a>
                @else
                <a href="{{ route('pages.contact') }}" class="btn btn-primary">
                    <i class="bi bi-envelope me-2"></i>تواصل معنا
                </a>
                @endauth
                <a href="tel:920001234" class="btn btn-outline-primary">
                    <i class="bi bi-telephone me-2"></i>920001234
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('faqSearch').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('.faq-item').forEach(item => {
        const question = item.querySelector('.accordion-button').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(query) || answer.includes(query)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

document.querySelectorAll('.helpful-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const faqId = this.dataset.faq;
        const helpful = this.dataset.helpful;
        
        fetch(`/faq/${faqId}/helpful`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ helpful: helpful === '1' })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                this.closest('.btn-group').innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>شكراً لتقييمك</span>';
            }
        });
    });
});
</script>
@endpush
@endsection
