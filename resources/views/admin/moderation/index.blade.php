@extends('admin.layouts.admin')
@section('content')
    <h1>Модерация</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#complaints">Жалобы</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#support">Обращения в поддержку</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="complaints">
            {{-- Форма фильтрации для жалоб --}}
            <form method="GET" action="{{ route('admin.moderation.index') }}" class="row g-2 mb-3 align-items-end">
                <input type="hidden" name="tab" value="complaints">
                <div class="col-md-3">
                    <label class="form-label">Статус</label>
                    <select name="complaint_status" class="form-select">
                        <option value="">Все</option>
                        <option value="pending" {{ request('complaint_status') == 'pending' ? 'selected' : '' }}>На рассмотрении</option>
                        <option value="reviewed" {{ request('complaint_status') == 'reviewed' ? 'selected' : '' }}>Принята</option>
                        <option value="rejected" {{ request('complaint_status') == 'rejected' ? 'selected' : '' }}>Отклонена</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Период</label>
                    <select name="complaint_date" class="form-select">
                        <option value="">Все</option>
                        <option value="today" {{ request('complaint_date') == 'today' ? 'selected' : '' }}>Сегодня</option>
                        <option value="week" {{ request('complaint_date') == 'week' ? 'selected' : '' }}>За 7 дней</option>
                        <option value="month" {{ request('complaint_date') == 'month' ? 'selected' : '' }}>За 30 дней</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Поиск (автор/причина)</label>
                    <input type="text" name="complaint_search" class="form-control" placeholder="Имя пользователя или причина" value="{{ request('complaint_search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Фильтр</button>
                </div>
            </form>

            @forelse($complaints as $complaint)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Жалоба от <strong>{{ $complaint->user->name }}</strong> на
                        @if($complaint->complaintable_type == 'App\Models\Post')
                            пост:
                            <a href="{{ route('forum.post', $complaint->complaintable_id) }}" target="_blank">
                                {{ $complaint->complaintable->title ?? 'удалён' }}
                            </a>
                        @else
                            комментарий
                            @if($complaint->complaintable && $complaint->complaintable->commentable_type == 'App\Models\Post')
                                к посту <a href="{{ route('forum.post', $complaint->complaintable->commentable_id) }}#comment-{{ $complaint->complaintable_id }}" target="_blank">
                                    {{ $complaint->complaintable->commentable->title ?? 'пост' }}
                                </a>
                                <br><small>Автор комментария: {{ $complaint->complaintable->user->name ?? 'удалён' }}</small>
                            @else
                                (удалён)
                            @endif
                        @endif
                    </span>
                        <span class="badge bg-{{ $complaint->status == 'reviewed' ? 'success' : ($complaint->status == 'rejected' ? 'danger' : 'warning') }}">
                        {{ $complaint->status == 'reviewed' ? 'Принята' : ($complaint->status == 'rejected' ? 'Отклонена' : 'На рассмотрении') }}
                    </span>
                    </div>
                    <div class="card-body">
                        <p><strong>Причина:</strong> {{ $complaint->reason }}</p>
                        <hr>
                        <p><strong>Содержимое:</strong></p>
                        <div class="bg-light p-2 rounded mb-2">
                            @if($complaint->complaintable)
                                @if($complaint->complaintable_type == 'App\Models\Post')
                                    <p><strong>Заголовок:</strong> {{ $complaint->complaintable->title }}</p>
                                    <p>{{ Str::limit($complaint->complaintable->content, 500) }}</p>
                                @else
                                    <p>{{ Str::limit($complaint->complaintable->content, 500) }}</p>
                                @endif
                            @else
                                <p class="text-danger">Контент уже удалён</p>
                            @endif
                        </div>
                        @if($complaint->status == 'pending')
                            <form method="POST" action="{{ route('admin.complaint.resolve', $complaint) }}" class="d-inline-block mt-2">
                                @csrf @method('PATCH')
                                <input type="text" name="moderator_comment" placeholder="Комментарий (необязательно)" class="form-control form-control-sm mb-2" style="max-width:300px;">
                                <button class="btn btn-success btn-sm">Принять (удалить контент)</button>
                            </form>
                            <form method="POST" action="{{ route('admin.complaint.reject', $complaint) }}" class="d-inline-block mt-2 ms-2">
                                @csrf @method('PATCH')
                                <button class="btn btn-danger btn-sm">Отклонить</button>
                            </form>
                        @else
                            <div class="alert alert-{{ $complaint->status == 'reviewed' ? 'success' : 'secondary' }} mt-2">
                                {{ $complaint->status == 'reviewed' ? 'Жалоба принята. Контент удалён.' : 'Жалоба отклонена.' }}
                                @if($complaint->moderator_comment)<br><strong>Комментарий модератора:</strong> {{ $complaint->moderator_comment }}@endif
                            </div>
                            @if($complaint->status == 'reviewed' && $complaint->complaintable && $complaint->complaintable->trashed())
                                <form method="POST" action="{{ route('admin.complaint.restore', $complaint) }}" class="d-inline-block mt-2">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-warning btn-sm">Восстановить контент</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Нет жалоб</div>
            @endforelse
            {{ $complaints->links() }}
        </div>

        <div class="tab-pane fade" id="support">
            {{-- Форма фильтрации для обращений --}}
            <form method="GET" action="{{ route('admin.moderation.index') }}" class="row g-2 mb-3 align-items-end">
                <input type="hidden" name="tab" value="support">
                <div class="col-md-3">
                    <label class="form-label">Статус</label>
                    <select name="support_status" class="form-select">
                        <option value="">Все</option>
                        <option value="new" {{ request('support_status') == 'new' ? 'selected' : '' }}>Новое</option>
                        <option value="in_progress" {{ request('support_status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                        <option value="closed" {{ request('support_status') == 'closed' ? 'selected' : '' }}>Закрыто</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label">Поиск (тема, сообщение, автор)</label>
                    <input type="text" name="support_search" class="form-control" placeholder="Поиск..." value="{{ request('support_search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Фильтр</button>
                </div>
            </form>

            @forelse($supportRequests as $req)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <strong>{{ $req->subject }}</strong>
                        <span class="badge bg-secondary">{{ $req->status }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>От:</strong> {{ $req->user->name }}</p>
                        <p>{{ $req->content }}</p>
                        @if($req->response)
                            <div class="alert alert-info">{{ $req->response }}</div>
                        @else
                            <form method="POST" action="{{ route('admin.support.respond', $req) }}">
                                @csrf @method('PATCH')
                                <textarea name="response" class="form-control mb-2" rows="2" required placeholder="Напишите ответ..."></textarea>
                                <button class="btn btn-primary btn-sm">Ответить и закрыть</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Нет обращений</div>
            @endforelse
            {{ $supportRequests->links() }}
        </div>
    </div>
@endsection
