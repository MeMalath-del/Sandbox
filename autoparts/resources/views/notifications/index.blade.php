@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-bell me-2"></i>الإشعارات</h1>
        <div class="d-flex gap-2">
            <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-check-all me-1"></i>تحديد الكل كمقروء
                </button>
            </form>
            <a href="{{ route('notifications.settings') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-gear me-1"></i>الإعدادات
            </a>
        </div>
    </div>

    @if($notifications->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            @foreach($notifications as $notification)
            <div class="list-group-item {{ !$notification->read_at ? 'bg-light' : '' }}">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-{{ !$notification->read_at ? 'primary' : 'secondary' }} bg-opacity-10 p-2">
                            <i class="bi {{ $notification->icon ?? 'bi-bell' }} text-{{ !$notification->read_at ? 'primary' : 'secondary' }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1 {{ !$notification->read_at ? 'fw-bold' : '' }}">{{ $notification->title }}</h6>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-muted">{{ $notification->message }}</p>
                        @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary mt-2">عرض التفاصيل</a>
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(!$notification->read_at)
                            <li>
                                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-check me-2"></i>تحديد كمقروء
                                    </button>
                                </form>
                            </li>
                            @endif
                            <li>
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i>حذف
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    {{ $notifications->links() }}
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-bell fs-1 text-muted"></i>
            <p class="text-muted mt-3">لا توجد إشعارات</p>
        </div>
    </div>
    @endif
</div>
@endsection
