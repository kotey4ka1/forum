@extends('admin.layouts.admin')
@section('content')
    <h1>Комментарии</h1>
    <form method="GET" action="{{ route('admin.comments.index') }}" class="row g-2 mb-3">
        <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Текст комментария" value="{{ request('search') }}"></div>
        <div class="col-md-3"><select name="post_id" class="form-select"><option value="">Все посты</option>@foreach($posts as $post)<option value="{{ $post->id }}" {{ request('post_id')==$post->id ? 'selected' : '' }}>{{ $post->title }}</option>@endforeach</select></div>
        <div class="col-md-3"><select name="user_id" class="form-select"><option value="">Все авторы</option>@foreach($users as $user)<option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected' : '' }}>{{ $user->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Фильтровать</button></div>
    </form>
    <table class="table table-bordered">
        <thead class="table-light"><tr><th>ID</th><th>Автор</th><th>Текст</th><th>Пост</th><th>Дата</th><th>Действия</th></tr></thead>
        <tbody>
        @forelse($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->user->name ?? 'Гость' }}</td>
                <td>{{ \Str::limit($comment->content, 80) }}</td>
                <td>{{ $comment->commentable->title ?? '—' }}</td>
                <td>{{ $comment->created_at->diffForHumans() }}</td>
                <td><form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Удалить?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Удалить</button></form></td>
            </tr>
        @empty
            <tr><td colspan="6">Нет комментариев</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $comments->links() }}
@endsection
