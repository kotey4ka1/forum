@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-4">Избранные посты</h1>
        @if($posts->count())
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-body">
                                <h5><a href="{{ route('forum.post', $post->id) }}" class="text-decoration-none">{{ $post->title }}</a></h5>
                                <div class="text-muted small mb-2">
                                    Раздел: <a href="{{ route('forum.section', $post->forum_section_id) }}">{{ $post->section->name ?? '?' }}</a>
                                </div>
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="bi bi-chat"></i> {{ $post->comments->count() }}</span>
                                    <span><i class="bi bi-eye"></i> {{ $post->views_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $posts->links() }}
        @else
            <div class="alert alert-info">У вас пока нет избранных постов.</div>
        @endif
    </div>
@endsection
