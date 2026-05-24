@extends('admin.layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Новая статья FAQ</div>
        <div class="card-body">
            <form action="{{ route('admin.faq.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Категория</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Вопрос</label>
                    <textarea name="question" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Ответ</label>
                    <textarea name="answer" class="form-control" rows="5" required></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_published" class="form-check-input" value="1" checked>
                    <label class="form-check-label">Опубликовать</label>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
@endsection
