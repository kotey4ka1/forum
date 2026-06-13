@extends('layouts.app')
@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Часто задаваемые вопросы</h1>

        {{-- Форма поиска и фильтрации --}}
        <form method="GET" action="{{ route('faq.index') }}" class="row g-2 mb-4">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Поиск по вопросам и ответам..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Все категории</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Найти</button>
            </div>
        </form>

        <div class="row">
            @forelse($articles as $article)
                <div class="col-md-6 mb-3">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bi bi-question-circle fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">
                                        <a href="{{ route('faq.show', $article) }}" class="text-decoration-none">{{ $article->question }}</a>
                                    </h5>
                                    <p class="card-text text-muted small mt-2">{{ Str::limit($article->answer, 100) }}</p>
                                    <span class="badge bg-secondary">{{ $article->category }}</span>
                                    <span class="text-muted ms-2"><i class="bi bi-eye"></i> {{ $article->views_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Ничего не найдено. Попробуйте другой запрос.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $articles->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
