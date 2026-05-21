@extends('admin.layouts.admin')
@section('content')
    <h1>Модерация</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#complaints">Жалобы</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#support">Обращения в поддержку</a></li>
    </ul>
    <div class="tab-content">
        {{-- Жалобы --}}
        <div class="tab-pane fade show active" id="complaints">
            @forelse($complaints as $complaint)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Жалоба от <strong>{{ $complaint->user->name }}</strong> на
                        {{ $complaint->complaintable_type == 'App\Models\Post' ? 'пост' : 'комментарий' }}
                    </span>
                        <span class="badge bg-warning">{{ $complaint->status }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>Причина:</strong> {{ $complaint->reason }}</p>
                        <hr>
                        <p><strong>Содержимое {{ $complaint->complaintable_type == 'App\Models\Post' ? 'поста' : 'комментария' }}:</strong></p>
                        <div class="bg-light p-2 rounded mb-2">
                            @if($complaint->complaintable)
                                @if($complaint->complaintable_type == 'App\Models\Post')
                                    <p><strong>Заголовок:</strong> {{ $complaint->complaintable->title }}</p>
                                    <p><strong>Текст:</strong> {{ \Str::limit($complaint->complaintable->content, 500) }}</p>
                                @else
                                    <p>{{ \Str::limit($complaint->complaintable->content, 500) }}</p>
                                @endif
                            @else
                                <p class="text-danger">Контент уже удалён.</p>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('admin.complaint.resolve', $complaint) }}" class="d-inline-block mt-2">
                            @csrf @method('PATCH')
                            <input type="text" name="moderator_comment" placeholder="Комментарий модератора (необязательно)" class="form-control form-control-sm mb-2" style="max-width: 300px;">
                            <button type="submit" class="btn btn-success btn-sm">Принять (удалить)</button>
                        </form>
                        <form method="POST" action="{{ route('admin.complaint.reject', $complaint) }}" class="d-inline-block mt-2 ms-2">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">Отклонить</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Нет жалоб</div>
            @endforelse
            {{ $complaints->links() }}
        </div>

        {{-- Обращения в поддержку --}}
        <div class="tab-pane fade" id="support">
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
                                <button type="submit" class="btn btn-primary btn-sm">Ответить и закрыть</button>
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
