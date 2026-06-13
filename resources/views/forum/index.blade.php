@extends('layouts.app')
@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Разделы форума</h1>
        <div class="row g-4">
            @foreach($sections as $index => $section)
                <div class="col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <a href="{{ route('forum.section', $section->id) }}" class="text-decoration-none text-dark">
                            <div class="card-img-top bg-white d-flex align-items-center justify-content-center" style="height: 250px;">
                                @if($section->image_url && Storage::disk('public')->exists($section->image_url))
                                    <img src="{{ asset('storage/app/public/' . $section->image_url) }}"
                                         alt="{{ $section->name }}"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                                        <i class="bi bi-grid fs-1 text-white"></i>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('forum.section', $section->id) }}" class="text-decoration-none text-dark">
                                    {{ $section->name }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                {{ Str::limit($section->description ?? 'Нет описания', 80) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="badge bg-secondary rounded-pill">
                                <i class="bi bi-chat"></i> Тем: {{ $section->posts_count ?? $section->posts->count() }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Реклама после каждого 3-го раздела, кроме последнего --}}
                @if(($index + 1) % 6 == 0 && !$loop->last)
                    <div class="col-12">
                        @include('partials.ad-between-posts')
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
