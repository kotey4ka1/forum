@extends('admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Посты</h1>
    </div>

    <!-- Форма фильтрации -->
    <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Заголовок..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="section_id" class="form-select">
                <option value="">Все разделы</option>
                @foreach($sections as $sec)
                    <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="user_id" class="form-select">
                <option value="">Все авторы</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Любые</option>
                <option value="pinned" {{ request('status') == 'pinned' ? 'selected' : '' }}>Закреплённые</option>
                <option value="unpinned" {{ request('status') == 'unpinned' ? 'selected' : '' }}>Обычные</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Фильтр</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Заголовок</th>
                <th>Автор</th>
                <th>Раздел</th>
                <th>Краткое содержание</th>
                <th>Просмотры</th>
                <th>Закреплён</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td><a href="{{ route('forum.post', $post->id) }}" target="_blank">{{ $post->title }}</a></td>
                    <td>{{ $post->user->name ?? 'Гость' }}</td>
                    <td>{{ $post->section->name ?? '—' }}</td>
                    <td>{{ Str::limit(strip_tags($post->content), 100) }}</td>
                    <td>{{ $post->views_count }}</td>
                    <td>{{ $post->is_pinned ? 'Да' : 'Нет' }}</td>
                    <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.posts.pin', $post) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary" title="{{ $post->is_pinned ? 'Открепить' : 'Закрепить' }}">
                                <i class="bi bi-pin"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.posts.move.form', $post) }}" class="btn btn-sm btn-info" title="Переместить">
                            <i class="bi bi-arrow-right-circle"></i>
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пост?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Удалить">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Постов не найдено</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $posts->appends(request()->query())->links() }}
@endsection
