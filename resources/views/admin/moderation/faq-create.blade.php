@extends('admin.layouts.admin')
@section('content')
    <div class="card"><div class="card-header">Новая статья FAQ</div><div class="card-body">
            <form method="POST" action="{{ route('admin.moderation.faq.store') }}">
                @csrf
                <div class="mb-3"><label>Категория</label><input type="text" name="category" class="form-control" required></div>
                <div class="mb-3"><label>Вопрос</label><input type="text" name="question" class="form-control" required></div>
                <div class="mb-3"><label>Ответ</label><textarea name="answer" rows="5" class="form-control" required></textarea></div>
                <div class="mb-3 form-check"><input type="checkbox" name="is_published" value="1" class="form-check-input" checked> <label class="form-check-label">Опубликовать</label></div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('admin.moderation.index') }}" class="btn btn-secondary">Назад</a>
            </form>
        </div></div>
@endsection
