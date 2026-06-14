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

        <!-- Фильтрация и сортировка -->
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
            <div class="row">
                @foreach($posts as $index => $post)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <!-- Изображение (если есть) -->
                            @php
                                $firstImage = $post->images->first();
                            @endphp
                            @if($firstImage)
                                <a href="{{ route('forum.post', $post->id) }}" class="text-decoration-none d-flex align-items-center justify-content-center" style="height: 250px; background-color: #f8f9fa;">
                                    <img src="{{ asset('storage/app/public/' . $firstImage->image_url) }}"
                                         class="img-fluid"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </a>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <a href="{{ route('forum.post', $post->id) }}" class="h5 text-decoration-none fw-semibold stretched-link">{{ $post->title }}</a>
                                </div>
                                <div class="small text-muted mb-2">
                                    <span><i class="bi bi-person"></i> {{ $post->user->name ?? 'Гость' }}</span>
                                    <span class="mx-2">•</span>
                                    <span><i class="bi bi-clock"></i> {{ $post->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="small text-muted">
                                        <span class="me-2"><i class="bi bi-chat"></i> {{ $post->comments_count ?? $post->comments->count() }}</span>
                                        <span class="me-2"><i class="bi bi-heart"></i> {{ $post->likes_count }}</span>
                                        <span><i class="bi bi-eye"></i> {{ $post->views_count }}</span>
                                        @if($post->is_pinned)
                                            <span class="badge bg-warning text-dark">📌 Закреплено</span>
                                        @endif
                                    </div>
                                    @auth
                                        @php $isFav = auth()->user()->favorites()->where('post_id', $post->id)->exists(); @endphp
                                        <form action="{{ route('post.favorite', $post->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $isFav ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill">
                                                <i class="bi bi-star-fill"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Реклама между постами: после каждого 5-го поста (кроме последнего) --}}
                    @if(($index + 1) % 9 == 0 && !$loop->last)
                        <div class="col-12">
                            @include('partials.ad-between-posts')
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-chat-square-text fs-1"></i>
                <p class="mb-0 mt-2">В этом разделе пока нет тем. Будьте первым!</p>
            </div>
        @endif
    </div>
@endsection
