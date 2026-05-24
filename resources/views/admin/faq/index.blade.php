@extends('admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Управление FAQ</h1>
        <a href="{{ route('admin.faq.create') }}" class="btn btn-success">+ Новая статья</a>
    </div>

    <form method="GET" action="{{ route('admin.faq.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="category" class="form-control" placeholder="Категория" value="{{ request('category') }}">
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Поиск по вопросу или ответу" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="is_published" class="form-select">
                <option value="">Все статусы</option>
                <option value="1" {{ request('is_published') == '1' ? 'selected' : '' }}>Опубликовано</option>
                <option value="0" {{ request('is_published') == '0' ? 'selected' : '' }}>Не опубликовано</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Фильтр</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr><th>ID</th><th>Категория</th><th>Вопрос</th><th>Опубликовано</th><th>Действия</th></tr>
        </thead>
        <tbody>
        @forelse($articles as $article)
            <tr>
                <td>{{ $article->id }}</td>
                <td>{{ $article->category }}</td>
                <td>{{ Str::limit($article->question, 70) }}</td>
                <td>{{ $article->is_published ? 'Да' : 'Нет' }}</td>
                <td>
                    <a href="{{ route('admin.faq.edit', $article) }}" class="btn btn-sm btn-primary">Ред.</a>
                    <form action="{{ route('admin.faq.destroy', $article) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить статью?')">Удалить</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Нет статей</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $articles->links() }}
@endsection
