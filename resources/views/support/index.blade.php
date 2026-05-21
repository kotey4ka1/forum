@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Мои обращения</h1>
            <a href="{{ route('support.create') }}" class="btn btn-primary">Новое обращение</a>
        </div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @forelse($requests as $req)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <strong>{{ $req->subject }}</strong>
                    <span class="badge bg-secondary">{{ $req->status }}</span>
                </div>
                <div class="card-body">
                    <p>{{ Str::limit($req->content, 200) }}</p>
                    <a href="{{ route('support.show', $req) }}" class="btn btn-sm btn-outline-secondary">Подробнее</a>
                </div>
            </div>
        @empty
            <div class="alert alert-info">У вас нет обращений.</div>
        @endforelse
        {{ $requests->links() }}
    </div>
@endsection
