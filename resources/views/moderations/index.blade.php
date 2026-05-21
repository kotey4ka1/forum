@extends('admin.layouts.admin')

@section('content')
    <h1>Модерация</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#complaints">Жалобы</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#support">Обращения в поддержку</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#faq">База знаний</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="complaints">
            @forelse($complaints as $complaint)
                <div class="card mb-3">
                    <div class="card-header">
                        Жалоба от {{ $complaint->user->name }} на
                        @if($complaint->complaintable_type == 'App\Models\Post')
                            пост: <a href="{{ route('forum.post', $complaint->complaintable_id) }}" target="_blank">{{ $complaint->complaintable->title ?? 'удалён' }}</a>
                        @else
                            комментарий
                        @endif
                        <span class="badge bg-warning">{{ $complaint->status }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>Причина:</strong> {{ $complaint->reason }}</p>
                        <form method="POST" action="{{ route('admin.complaint.resolve', $complaint) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <input type="text" name="moderator_comment" placeholder="Комментарий" class="form-control mb-2" style="width:300px;">
                            <button class="btn btn-success btn-sm">Принять</button>
                        </form>
                        <form method="POST" action="{{ route('admin.complaint.reject', $complaint) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-danger btn-sm">Отклонить</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Нет жалоб</div>
            @endforelse
            {{ $complaints->links() }}
        </div>
        <div class="tab-pane fade" id="support">
            @forelse($supportRequests as $req)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <strong>{{ $req->subject }}</strong>
                        <span class="badge bg-secondary">{{ $req->status }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>От:</strong> {{ $req->user->name }} ({{ $req->user->email }})</p>
                        <p>{{ $req->content }}</p>
                        @if($req->response)
                            <div class="alert alert-info"><strong>Ответ:</strong> {{ $req->response }}</div>
                        @else
                            <form method="POST" action="{{ route('admin.support.respond', $req) }}">
                                @csrf @method('PATCH')
                                <textarea name="response" class="form-control mb-2" rows="3" required placeholder="Ваш ответ..."></textarea>
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
        <div class="tab-pane fade" id="faq">
            <a href="{{ route('admin.moderation.faq.create') }}" class="btn btn-success mb-3">+ Новая статья</a>
            <table class="table table-bordered">
                <thead><tr><th>Категория</th><th>Вопрос</th><th>Опубликовано</th><th>Действия</th></tr></thead>
                <tbody>
                @foreach($faqArticles ?? [] as $faq)
                    <tr>
                        <td>{{ $faq->category }}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ $faq->is_published ? 'Да' : 'Нет' }}</td>
                        <td>
                            <a href="{{ route('admin.moderation.faq.edit', $faq) }}" class="btn btn-sm btn-primary">Ред.</a>
                            <form action="{{ route('admin.moderation.faq.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $faqArticles->links() ?? '' }}
        </div>
    </div>
@endsection
