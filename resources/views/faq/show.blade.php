@extends('layouts.app')
@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-0 pt-4">
                <h4 class="mb-0">{{ $knowledgeBase->question }}</h4>
                <div class="mt-2">
                    <span class="badge bg-secondary">{{ $knowledgeBase->category }}</span>
                    <span class="text-muted ms-2"><i class="bi bi-eye"></i> {{ $knowledgeBase->views_count }} просмотров</span>
                </div>
            </div>
            <div class="card-body p-4">
                <p class="mb-0">{{ $knowledgeBase->answer }}</p>
            </div>
            <div class="card-footer bg-white border-0 pb-4">
                <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Назад к списку
                </a>
            </div>
        </div>
    </div>
@endsection
