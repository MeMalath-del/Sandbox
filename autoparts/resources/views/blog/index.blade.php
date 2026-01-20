@extends('layouts.app')

@section('title', 'المدونة')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">المدونة</h1>
            <p class="text-muted lead">أحدث الأخبار والنصائح حول قطع غيار السيارات وصيانتها</p>
        </div>
        <div class="col-lg-4">
            <form action="{{ route('blog.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="ابحث في المدونة..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Posts -->
        <div class="col-lg-8">
            @if($posts->count() > 0)
            <div class="row g-4">
                @foreach($posts as $post)
                <div class="col-md-6">
                    <article class="card h-100 border-0 shadow-sm overflow-hidden">
                        @if($post->featured_image)
                        <a href="{{ route('blog.show', $post->slug) }}">
                            <img src="{{ $post->image_url }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                        </a>
                        @endif
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                @if($post->category)
                                <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-primary text-decoration-none me-2">
                                    {{ $post->category->name }}
                                </a>
                                @endif
                                <small class="text-muted">{{ $post->published_at?->diffForHumans() }}</small>
                            </div>
                            <h5 class="card-title">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted">{{ Str::limit($post->excerpt, 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=32" class="rounded-circle me-2" width="32" height="32">
                                <small>{{ $post->author->name }}</small>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-eye me-1"></i>{{ number_format($post->views_count) }}
                            </small>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-journal-text fs-1 text-muted"></i>
                <p class="text-muted mt-3">لا توجد مقالات</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-folder me-2"></i>التصنيفات</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        {{ $category->name }}
                        <span class="badge bg-secondary rounded-pill">{{ $category->posts_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Popular Posts -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-fire me-2"></i>الأكثر قراءة</h6>
                </div>
                <div class="card-body">
                    @foreach($popularPosts as $popular)
                    <div class="d-flex mb-3">
                        @if($popular->featured_image)
                        <img src="{{ $popular->image_url }}" class="rounded me-3" width="80" height="60" style="object-fit: cover;">
                        @endif
                        <div>
                            <a href="{{ route('blog.show', $popular->slug) }}" class="text-dark text-decoration-none fw-medium">
                                {{ Str::limit($popular->title, 50) }}
                            </a>
                            <small class="text-muted d-block">{{ $popular->published_at?->format('Y/m/d') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tags -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-tags me-2"></i>الوسوم</h6>
                </div>
                <div class="card-body">
                    @foreach($tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
