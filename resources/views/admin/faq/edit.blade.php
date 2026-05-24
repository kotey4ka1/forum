@extends('admin.layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Редактирование статьи FAQ</div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.faq.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Категория</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $faq->category) }}" required>
                </div>
                <div class="mb-3">
                    <label>Вопрос</label>
                    <textarea name="question" class="form-control" rows="2" required>{{ old('question', $faq->question) }}</textarea>
                </div>
                <div class="mb-3">
                    <label>Ответ</label>
                    <textarea name="answer" class="form-control" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ $faq->is_published ? 'checked' : '' }}>
                    <label class="form-check-label">Опубликовать</label>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
                <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
@endsection
