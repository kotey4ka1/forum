@extends('admin.layouts.admin')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Разделы форума</h1>
        <a href="{{ route('admin.sections.create') }}" class="btn btn-success">+ Создать раздел</a>
    </div>
    <form method="GET" action="{{ route('admin.sections.index') }}" class="row g-2 mb-3">
        <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Название раздела" value="{{ request('search') }}"></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary">Фильтровать</button></div>
    </form>
    <table class="table table-bordered">
        <thead class="table-light"><tr><th>ID</th><th>Название</th><th>Описание</th><th>Действия</th></tr></thead>
        <tbody>
        @forelse($sections as $section)
            <tr>
                <td>{{ $section->id }}</td>
                <td>{{ $section->name }}</td>
                <td>{{ $section->description ?? '—' }}</td>
                <td><a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-primary">Ред.</a>
                    <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить раздел?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Уд.</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Нет разделов</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $sections->links() }}
@endsection
