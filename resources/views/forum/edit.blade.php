@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Редактирование поста: {{ $post->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('forum.post.update', $post->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Заголовок</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Содержание</label>
                                <textarea name="content" class="form-control" rows="8" required>{{ old('content', $post->content) }}</textarea>
                            </div>

                            @if($post->images && $post->images->count())
                                <div class="mb-3">
                                    <label class="form-label">Текущие изображения (можно отметить для удаления)</label>
                                    <div class="row">
                                        @foreach($post->images as $image)
                                            <div class="col-md-3 mb-2 position-relative">
                                                <img src="{{ asset('storage/' . $image->image_url) }}" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                                                    <label class="form-check-label">Удалить</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Добавить новые изображения (до 5, jpg, png, gif, до 4 МБ)</label>
                                <input type="file" name="new_images[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Можно выбрать несколько файлов.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                <a href="{{ route('forum.post', $post->id) }}" class="btn btn-secondary">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
