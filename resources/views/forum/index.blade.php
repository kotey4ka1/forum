@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mb-4">Разделы форума</h1>
                <div class="row">
                    @foreach($sections as $section)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('forum.section', $section->id) }}">{{ $section->name }}</a>
                                    </h5>
                                    <p class="card-text">{{ $section->description }}</p>
                                    <small class="text-muted">Тем: {{ $section->posts->count() }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 mt-5">
                @include('partials.ad-sidebar')
            </div>
        </div>
    </div>
@endsection
