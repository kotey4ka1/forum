@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        {{-- Аватар пользователя --}}
                        @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                            <img src="{{ asset('storage/app/public/' . $user->avatar) }}" class="img-thumbnail rounded-circle" width="150" height="150" alt="avatar">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto" style="width:150px;height:150px;">
                                <span class="text-white display-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif

                        <h4 class="mt-3">{{ $user->name }}</h4>
                        @if($user->role)
                            <span class="badge bg-secondary">{{ $user->role->display_name ?? $user->role->name }}</span>
                        @endif
                        <p class="text-muted mt-2">Дата регистрации: {{ $user->created_at->format('d.m.Y') }}</p>
                        <p class="text-muted">Последний визит: {{ $lastSeen }}</p>

                        @if(Auth::id() == $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-2 rounded-pill">Редактировать профиль</a>
                        @endif

                        @if(Auth::user() && Auth::user()->isAdmin() && Auth::id() != $user->id)
                            <hr>
                            <form action="{{ route('admin.toggleBan', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill">{{ $user->is_banned ? 'Разблокировать' : 'Заблокировать' }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3 mb-3">
                    <div class="card-header bg-white">Статистика</div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col"><h5>{{ $postsCount }}</h5><small>Тем создано</small></div>
                            <div class="col"><h5>{{ $commentsCount }}</h5><small>Комментариев</small></div>
                            <div class="col"><h5>{{ $likesGiven }}</h5><small>Лайков поставлено</small></div>
                            <div class="col"><h5>{{ $totalLikesReceived }}</h5><small>Лайков получено</small></div>
                        </div>
                    </div>
                </div>

                @if($recentPosts->count())
                    <div class="card shadow-sm border-0 rounded-3 mb-3">
                        <div class="card-header bg-white">Последние темы</div>
                        <ul class="list-group list-group-flush">
                            @foreach($recentPosts as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('forum.post', $post->id) }}">{{ $post->title }}</a>
                                    <small class="text-muted float-end">{{ $post->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($recentComments->count())
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white">Последние комментарии</div>
                        <ul class="list-group list-group-flush">
                            @foreach($recentComments as $comment)
                                <li class="list-group-item">
                                    <a href="{{ route('forum.post', $comment->commentable_id) }}#comment-{{ $comment->id }}">
                                        {{ Str::limit($comment->content, 80) }}
                                    </a>
                                    <small class="text-muted float-end">{{ $comment->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
