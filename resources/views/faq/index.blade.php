@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Часто задаваемые вопросы</h1>
        <div class="list-group">
            @foreach($articles as $article)
                <a href="{{ route('faq.show', $article) }}" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">{{ $article->question }}</h5>
                    <small class="text-muted">{{ $article->category }}</small>
                </a>
            @endforeach
        </div>
        {{ $articles->links() }}
    </div>
@endsection
