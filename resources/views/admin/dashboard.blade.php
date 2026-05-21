@extends('admin.layouts.admin')
@section('content')
    <h1 class="h3 mb-4">Дашборд</h1>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="card text-center"><div class="card-body"><h3>{{ $stats['users'] }}</h3><p class="mb-0">Пользователей</p></div></div></div>
        <div class="col-6 col-md-3"><div class="card text-center"><div class="card-body"><h3>{{ $stats['posts'] }}</h3><p class="mb-0">Постов</p></div></div></div>
        <div class="col-6 col-md-3"><div class="card text-center"><div class="card-body"><h3>{{ $stats['comments'] }}</h3><p class="mb-0">Комментариев</p></div></div></div>
        <div class="col-6 col-md-3"><div class="card text-center"><div class="card-body"><h3>{{ $stats['sections'] }}</h3><p class="mb-0">Разделов</p></div></div></div>
    </div>
    <div class="row g-3">
        <div class="col-md-6"><div class="card"><div class="card-header">Последние пользователи</div><ul class="list-group list-group-flush">@foreach($recentUsers as $user)<li class="list-group-item">{{ $user->name }} ({{ $user->email }})</li>@endforeach</ul></div></div>
        <div class="col-md-6"><div class="card"><div class="card-header">Последние посты</div><ul class="list-group list-group-flush">@foreach($recentPosts as $post)<li class="list-group-item"><a href="{{ route('forum.post', $post->id) }}">{{ $post->title }}</a> от {{ $post->user->name ?? 'Гость' }}</li>@endforeach</ul></div></div>
    </div>
@endsection
