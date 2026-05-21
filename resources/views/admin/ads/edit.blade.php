@extends('admin.layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header"><h4>Редактирование баннера: {{ $ad->name }}</h4></div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>@endif
            <form action="{{ route('admin.ads.update', $ad) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Название</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $ad->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Изображение (оставьте пустым, если не хотите менять)</label>
                    <input type="file" name="content" class="form-control" accept="image/*">
                    @if($ad->content)
                        <div class="mt-2">
                            <img src="{{ asset('storage/app/public/' . $ad->content) }}" width="100" class="img-thumbnail">
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label">Целевая ссылка</label>
                    <input type="url" name="target_url" class="form-control" value="{{ old('target_url', $ad->target_url) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Место размещения</label>
                    <select name="placement_key" class="form-select" required>
                        <option value="sidebar" {{ old('placement_key', $ad->placement_key) == 'sidebar' ? 'selected' : '' }}>Сайдбар</option>
                        <option value="between_posts" {{ old('placement_key', $ad->placement_key) == 'between_posts' ? 'selected' : '' }}>Между постами</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Вес (ротация)</label>
                    <input type="number" name="weight" class="form-control" value="{{ old('weight', $ad->weight) }}" min="0" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Активен</label>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
                <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
@endsection
