@extends('admin.layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header"><h4>Редактирование раздела: {{ $section->name }}</h4></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('admin.sections.update', $section) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Название</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $section->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $section->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Изображение</label>
                    @if($section->image_url && Storage::disk('public')->exists($section->image_url))
                        <div class="mb-2">
                            <img src="{{ asset('storage/app/public/' . $section->image_url) }}" width="100" class="img-thumbnail">
                            <div class="form-text">Текущее изображение. Загрузите новое, чтобы заменить.</div>
                        </div>
                    @endif
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <small class="form-text text-muted">Поддерживаются JPG, PNG, GIF (макс. 2 МБ).</small>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
                <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
@endsection
