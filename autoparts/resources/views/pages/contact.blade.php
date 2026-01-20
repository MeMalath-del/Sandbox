@extends('layouts.app')

@section('title', 'اتصل بنا')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3">تواصل معنا</h1>
        <p class="text-muted lead">نحن هنا لمساعدتك. تواصل معنا وسنرد عليك في أقرب وقت</p>
    </div>

    <div class="row g-5">
        <!-- Contact Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-4">معلومات الاتصال</h5>
                    
                    <div class="d-flex mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-geo-alt text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">العنوان</h6>
                            <p class="text-muted mb-0">الرياض، المملكة العربية السعودية</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="bi bi-telephone text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">الهاتف</h6>
                            <p class="text-muted mb-0" dir="ltr">+966 920001234</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-envelope text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">البريد الإلكتروني</h6>
                            <p class="text-muted mb-0">support@autoparts.sa</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="bi bi-clock text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">ساعات العمل</h6>
                            <p class="text-muted mb-0">24/7 - خدمة عملاء متواصلة</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">تابعنا على</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-dark btn-sm"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-success btn-sm"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-4">أرسل لنا رسالة</h5>

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()?->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()?->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الموضوع <span class="text-danger">*</span></label>
                                <select name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                                    <option value="">اختر الموضوع</option>
                                    <option value="استفسار عام">استفسار عام</option>
                                    <option value="الطلبات والشحن">الطلبات والشحن</option>
                                    <option value="المنتجات">المنتجات</option>
                                    <option value="الشراكات">الشراكات والتعاون</option>
                                    <option value="شكوى">شكوى</option>
                                    <option value="اقتراح">اقتراح</option>
                                    <option value="أخرى">أخرى</option>
                                </select>
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">الرسالة <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>إرسال الرسالة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="mt-5 pt-5">
        <div class="text-center mb-4">
            <h3>الأسئلة الشائعة</h3>
            <p class="text-muted">قد تجد إجابة سؤالك هنا</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                كيف يمكنني تتبع طلبي؟
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                يمكنك تتبع طلبك من خلال صفحة "طلباتي" في حسابك، أو باستخدام رقم الطلب في صفحة التتبع.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ما هي سياسة الإرجاع؟
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                يمكنك إرجاع المنتج خلال 14 يومًا من تاريخ الاستلام بشرط أن يكون بحالته الأصلية.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 shadow-sm rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                هل تتوفر لديكم خدمة التوصيل؟
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                نعم، نوفر خدمة التوصيل لجميع مناطق المملكة. التوصيل مجاني للطلبات التي تزيد عن 200 ر.س.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('faq.index') }}" class="btn btn-outline-primary">عرض جميع الأسئلة الشائعة</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
