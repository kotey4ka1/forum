@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">{{ $knowledgeBase->question }} <small class="text-muted">{{ $knowledgeBase->category }}</small></div>
            <div class="card-body"><p>{{ $knowledgeBase->answer }}</p></div>
        </div>
        <a href="{{ route('faq.index') }}" class="btn btn-secondary mt-3">Назад</a>
    </div>
@endsection
