@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>База знаний</h1>
        <div class="row">
            @foreach($articles as $article)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $article->category }}</div>
                        <div class="card-body">
                            <h5><a href="{{ route('faq.show', $article) }}">{{ $article->question }}</a></h5>
                            <p>{{ Str::limit($article->answer, 150) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $articles->links() }}
    </div>
@endsection
