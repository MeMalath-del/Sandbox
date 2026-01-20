@extends('layouts.app')

@section('title', 'الرسائل')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-envelope me-2"></i>الرسائل</h1>
        <a href="{{ route('messages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>رسالة جديدة
        </a>
    </div>

    @if($conversations->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            @foreach($conversations as $conversation)
            @php
                $otherUser = $conversation->getOtherUser(auth()->user());
                $unread = $conversation->unreadCountFor(auth()->user());
            @endphp
            <a href="{{ route('messages.show', $conversation) }}" class="list-group-item list-group-item-action {{ $unread > 0 ? 'bg-light' : '' }}">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&size=50" class="rounded-circle" width="50" height="50">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1 {{ $unread > 0 ? 'fw-bold' : '' }}">{{ $otherUser->name }}</h6>
                            <small class="text-muted">{{ $conversation->last_message_at?->diffForHumans() }}</small>
                        </div>
                        @if($conversation->lastMessage)
                        <p class="mb-0 text-muted small {{ $unread > 0 ? 'fw-medium text-dark' : '' }}">
                            {{ Str::limit($conversation->lastMessage->message, 50) }}
                        </p>
                        @endif
                    </div>
                    @if($unread > 0)
                    <span class="badge bg-primary rounded-pill">{{ $unread }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    {{ $conversations->links() }}
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-envelope fs-1 text-muted"></i>
            <p class="text-muted mt-3">لا توجد رسائل</p>
            <a href="{{ route('messages.create') }}" class="btn btn-primary">إرسال رسالة</a>
        </div>
    </div>
    @endif
</div>
@endsection
