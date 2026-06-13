@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Результаты поиска по запросу: "{{ $query }}"</h1>

        {{-- Разделы --}}
        @if($sections->count())
            <div class="mb-5">
                <h3>Разделы форума</h3>
                <div class="row">
                    @foreach($sections as $section)
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-body">
                                    <h5><a href="{{ route('forum.section', $section->id) }}" class="text-decoration-none">{{ $section->name }}</a></h5>
                                    <p class="text-muted mb-0">{{ Str::limit($section->description, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Посты --}}
        @if($posts->count())
            <h3>Посты</h3>
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5><a href="{{ route('forum.post', $post->id) }}" class="text-decoration-none">{{ $post->title }}</a></h5>
                                    @auth
                                        @php $isFav = auth()->user()->favorites()->where('post_id', $post->id)->exists(); @endphp
                                        <form action="{{ route('post.favorite', $post->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $isFav ? 'btn-warning' : 'btn-outline-warning' }} rounded-pill">
                                                <i class="bi bi-star-fill"></i> {{ $isFav ? 'В избранном' : 'В избранное' }}
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <div class="text-muted small mb-2">
                                    Раздел: <a href="{{ route('forum.section', $post->forum_section_id) }}">{{ $post->section->name ?? '?' }}</a>
                                    | Автор: {{ $post->user->name ?? 'Гость' }}
                                    | {{ $post->created_at->diffForHumans() }}
                                </div>
                                <p>{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="bi bi-chat"></i> {{ $post->comments->count() }}</span>
                                    <span><i class="bi bi-eye"></i> {{ $post->views_count }}</span>
                                    <span><i class="bi bi-heart"></i> {{ $post->likes_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif

        @if($posts->count() == 0 && $sections->count() == 0)
            <div class="alert alert-info">По вашему запросу ничего не найдено.</div>
        @endif
    </div>
@endsection
