@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Создание новой темы в разделе: {{ $section->name }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('forum.post.store', $section->id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Заголовок</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Содержание</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- НОВЫЙ БЛОК: Артикулы (теги) -->
                            <div class="mb-3">
                                <label for="tags" class="form-label">Артикулы (через запятую)</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}" placeholder="Пример: куртка, пуховик, базовая модель">
                                @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Вы можете добавить несколько артикулов, разделяя их запятыми.</div>
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Изображения (до 5 шт., jpg, png, gif, до 2 МБ каждый)</label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" accept="image/*" multiple>
                                @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Можно выбрать несколько файлов.</div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Опубликовать</button>
                                <a href="{{ route('forum.section', $section->id) }}" class="btn btn-secondary">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
