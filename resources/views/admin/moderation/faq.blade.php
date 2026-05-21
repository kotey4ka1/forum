@extends('admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>База знаний (FAQ)</h1>
        <a href="{{ route('admin.moderation.faq.create') }}" class="btn btn-success">+ Новая статья</a>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr><th>Категория</th><th>Вопрос</th><th>Опубликовано</th><th>Действия</th></tr>
        </thead>
        <tbody>
        @forelse($faqArticles as $faq)
            <tr>
                <td>{{ $faq->category }}</td>
                <td>{{ $faq->question }}</td>
                <td>{{ $faq->is_published ? 'Да' : 'Нет' }}</td>
                <td>
                    <a href="{{ route('admin.moderation.faq.edit', $faq) }}" class="btn btn-sm btn-primary">Ред.</a>
                    <form action="{{ route('admin.moderation.faq.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Нет статей. <a href="{{ route('admin.moderation.faq.create') }}">Создать первую</a></td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $faqArticles->links() }}
@endsection
