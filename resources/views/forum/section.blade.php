@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>{{ $section->name }}</h1>
            @auth
                <a href="{{ route('forum.post.create', $section->id) }}" class="btn btn-primary rounded-pill">➕ Новая тема</a>
            @endauth
        </div>
        <p class="text-muted">{{ $section->description }}</p>

        <!-- Форма фильтрации и сортировки -->
        <form method="GET" action="{{ route('forum.section', $section->id) }}" class="row g-2 mb-3 align-items-end">
            <div class="col-auto">
                <label class="form-label">Сортировать по</label>
                <select name="sort" class="form-select">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Дате</option>
                    <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Просмотрам</option>
                    <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Лайкам</option>
                    <option value="comments" {{ request('sort') == 'comments' ? 'selected' : '' }}>Количеству ответов</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label">Порядок</label>
                <select name="order" class="form-select">
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Сначала новые</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Сначала старые</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label">Закреплённые</label>
                <select name="pinned" class="form-select">
                    <option value="">Все темы</option>
                    <option value="only" {{ request('pinned') == 'only' ? 'selected' : '' }}>Только закреплённые</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Применить</button>
            </div>
        </form>

        @if($posts->count())
            <div class="list-group shadow-sm">
                @foreach($posts as $post)
                    <div class="list-group-item list-group-item-action d-flex flex-column flex-md-row justify-content-between align-items-md-center p-3 mb-2 rounded-3">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                @if($post->is_pinned)
                                    <span class="badge bg-warning text-dark">📌 Закреплено</span>
                                @endif
                                <a href="{{ route('forum.post', $post->id) }}" class="h5 text-decoration-none fw-semibold">{{ $post->title }}</a>
                            </div>
                            <div class="small text-muted d-flex flex-wrap gap-3">
                                <span><i class="bi bi-person"></i> {{ $post->user->name ?? 'Гость' }}</span>
                                <span><i class="bi bi-chat"></i> Ответов: {{ $post->comments_count ?? $post->comments->count() }}</span>
                                <span><i class="bi bi-heart"></i> Лайков: {{ $post->likes_count }}</span>
                                <span><i class="bi bi-eye"></i> Просмотров: {{ $post->views_count }}</span>
                            </div>
                        </div>
                        <div class="text-md-end mt-2 mt-md-0">
                            <small class="text-muted"><i class="bi bi-clock"></i> {{ $post->updated_at->diffForHumans() }}</small>
                            @auth
                                @php $isFav = auth()->user()->favorites()->where('post_id', $post->id)->exists(); @endphp
                                <form action="{{ route('post.favorite', $post->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $isFav ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill">
                                        <i class="bi bi-star-fill"></i> {{ $isFav ? 'В избранном' : 'В избранное' }}
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-chat-square-text fs-1"></i>
                <p class="mb-0 mt-2">В этом разделе пока нет тем. Будьте первым!</p>
            </div>
        @endif
    </div>
@endsection
