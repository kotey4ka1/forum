@extends('admin.layouts.admin')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Рекламные материалы</h1>
        <a href="{{ route('admin.ads.create') }}" class="btn btn-success">+ Создать</a>
    </div>
    <form method="GET" action="{{ route('admin.ads.index') }}" class="row g-2 mb-3">
        <div class="col-md-2"><input type="text" name="search" class="form-control" placeholder="Название" value="{{ request('search') }}"></div>
        <div class="col-md-2"><select name="type" class="form-select"><option value="">Все типы</option><option value="banner" {{ request('type')=='banner' ? 'selected' : '' }}>Баннер</option><option value="video" {{ request('type')=='video' ? 'selected' : '' }}>Видео</option></select></div>
        <div class="col-md-2"><select name="placement_key" class="form-select"><option value="">Все места</option><option value="header" {{ request('placement_key')=='header' ? 'selected' : '' }}>Шапка</option><option value="sidebar" {{ request('placement_key')=='sidebar' ? 'selected' : '' }}>Сайдбар</option><option value="between_posts" {{ request('placement_key')=='between_posts' ? 'selected' : '' }}>Между постами</option></select></div>
        <div class="col-md-2"><select name="is_active" class="form-select"><option value="">Все</option><option value="1" {{ request('is_active')=='1' ? 'selected' : '' }}>Активны</option><option value="0" {{ request('is_active')=='0' ? 'selected' : '' }}>Неактивны</option></select></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Фильтровать</button></div>
    </form>
    <table class="table table-bordered">
        <thead class="table-light"><tr><th>ID</th><th>Название</th><th>Тип</th><th>Место</th><th>Вес</th><th>Активен</th><th>Действия</th></tr></thead>
        <tbody>
        @forelse($ads as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->placement_key }}</td>
                <td>{{ $item->weight }}</td>
                <td>{{ $item->is_active ? 'Да' : 'Нет' }}</td>
                <td><a href="{{ route('admin.ads.edit', $item) }}" class="btn btn-sm btn-primary">Ред.</a>
                    <a href="{{ route('admin.ads.stats', $item) }}" class="btn btn-sm btn-info">Стат.</a>
                    <form action="{{ route('admin.ads.destroy', $item) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Уд.</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Нет материалов</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $ads->links() }}
@endsection
