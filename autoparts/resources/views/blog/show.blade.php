@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <article>
                <!-- Featured Image -->
                @if($post->featured_image)
                <img src="{{ $post->image_url }}" class="img-fluid rounded-4 mb-4 w-100" alt="{{ $post->title }}" style="max-height: 400px; object-fit: cover;">
                @endif

                <!-- Meta -->
                <div class="d-flex align-items-center mb-4">
                    @if($post->category)
                    <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-primary text-decoration-none me-3">
                        {{ $post->category->name }}
                    </a>
                    @endif
                    <span class="text-muted me-3">
                        <i class="bi bi-calendar me-1"></i>{{ $post->published_at?->format('Y/m/d') }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-eye me-1"></i>{{ number_format($post->views_count) }} مشاهدة
                    </span>
                </div>

                <!-- Title -->
                <h1 class="display-5 fw-bold mb-4">{{ $post->title }}</h1>

                <!-- Author -->
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=50" class="rounded-circle me-3" width="50" height="50">
                    <div>
                        <strong>{{ $post->author->name }}</strong>
                        <small class="text-muted d-block">كاتب المقال</small>
                    </div>
                </div>

                <!-- Content -->
                <div class="article-content fs-5 lh-lg">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                <div class="mt-4 pt-4 border-top">
                    <i class="bi bi-tags me-2"></i>
                    @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" class="badge bg-light text-dark me-1 text-decoration-none">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                <!-- Share -->
                <div class="mt-4 pt-4 border-top">
                    <h6 class="mb-3">شارك المقال</h6>
                    <div class="d-flex gap-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-outline-dark">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-outline-success">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}')" class="btn btn-outline-secondary">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>

                <!-- Comments -->
                <div class="mt-5 pt-4 border-top">
                    <h4 class="mb-4">التعليقات ({{ $comments->total() }})</h4>

                    @auth
                    <form action="{{ route('blog.comment', $post) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" rows="3" class="form-control" placeholder="أضف تعليقك..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">إرسال التعليق</button>
                    </form>
                    @else
                    <div class="alert alert-light mb-4">
                        <a href="{{ route('login') }}">سجل الدخول</a> لإضافة تعليق
                    </div>
                    @endauth

                    @foreach($comments as $comment)
                    <div class="d-flex mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&size=40" class="rounded-circle me-3" width="40" height="40">
                        <div class="flex-grow-1">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>

                            <!-- Replies -->
                            @foreach($comment->replies as $reply)
                            <div class="d-flex mt-3 ms-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&size=32" class="rounded-circle me-2" width="32" height="32">
                                <div class="flex-grow-1">
                                    <div class="bg-white border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>{{ $reply->user->name }}</strong>
                                            <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0">{{ $reply->content }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    {{ $comments->links() }}
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">مقالات ذات صلة</h6>
                </div>
                <div class="card-body">
                    @foreach($relatedPosts as $related)
                    <div class="d-flex mb-3">
                        @if($related->featured_image)
                        <img src="{{ $related->image_url }}" class="rounded me-3" width="80" height="60" style="object-fit: cover;">
                        @endif
                        <div>
                            <a href="{{ route('blog.show', $related->slug) }}" class="text-dark text-decoration-none fw-medium">
                                {{ Str::limit($related->title, 50) }}
                            </a>
                            <small class="text-muted d-block">{{ $related->published_at?->format('Y/m/d') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Newsletter -->
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-envelope fs-1 mb-3"></i>
                    <h5>اشترك في النشرة البريدية</h5>
                    <p class="small opacity-75">احصل على أحدث المقالات والنصائح</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="بريدك الإلكتروني" required>
                            <button type="submit" class="btn btn-light">اشترك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
