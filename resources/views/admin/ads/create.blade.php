@extends('admin.layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header"><h4>Создание баннера</h4></div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>@endif
            <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Название</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Изображение (jpg, png, gif, до 5 МБ)</label>
                    <input type="file" name="content" class="form-control" accept="image/*" required>
                    <small class="text-muted">Рекомендуемые размеры: для сайдбара 300×250, между постами 728×150.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Целевая ссылка</label>
                    <input type="url" name="target_url" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Место размещения</label>
                    <select name="placement_key" class="form-select" required>
                        <option value="sidebar">Сайдбар (300×250)</option>
                        <option value="between_posts">Между постами (728×150)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Вес (ротация)</label>
                    <input type="number" name="weight" class="form-control" value="1" min="0" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                    <label class="form-check-label">Активен</label>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
@endsection
