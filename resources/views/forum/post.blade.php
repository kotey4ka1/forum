@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-3">
            <a href="{{ route('forum.section', $post->forum_section_id) }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left"></i> Назад к разделу</a>
        </div>

        <h1 class="mb-3">{{ $post->title }}</h1>
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 text-muted">
            <div><i class="bi bi-person"></i> {{ $post->user->name ?? 'Гость' }}</div>
            <div><i class="bi bi-eye"></i> {{ $post->views_count }} просмотров</div>
            <div><i class="bi bi-calendar3"></i> {{ $post->created_at->format('d.m.Y H:i') }}</div>
        </div>

        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>

        @if($post->images && $post->images->count())
            <div class="mb-4">
                <h5 class="mb-3">Изображения</h5>
                <div class="row g-3">
                    @foreach($post->images as $image)
                        <div class="col-6 col-md-3">
                            <a href="{{ asset('storage/app/public/' . $image->image_url) }}" target="_blank" class="d-block rounded overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/app/public/' . $image->image_url) }}" class="img-fluid w-100" style="height: 120px; object-fit: cover;">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="d-flex gap-2 mb-4">
            @auth
                <button class="btn btn-sm rounded-pill like-btn {{ Auth::user()->likes()->where('likeable_id', $post->id)->where('likeable_type', 'App\Models\Post')->exists() ? 'btn-danger' : 'btn-outline-danger' }}" data-type="post" data-id="{{ $post->id }}">
                    <i class="bi bi-heart"></i> <span class="likes-count">{{ $post->likes_count ?? 0 }}</span>
                </button>
                @php $isFav = Auth::user()->favorites()->where('post_id', $post->id)->exists(); @endphp
                <form action="{{ route('post.favorite', $post->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-pill {{ $isFav ? 'btn-warning' : 'btn-outline-warning' }}">
                        <i class="bi bi-star-fill"></i> {{ $isFav ? 'В избранном' : 'В избранное' }}
                    </button>
                </form>
                @if(Auth::id() === $post->user_id || Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <a href="{{ route('forum.post.edit', $post->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill">Редактировать пост</a>
                    <form action="{{ route('forum.post.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пост?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm rounded-pill">Удалить пост</button>
                    </form>
                @endif
                <button class="btn btn-outline-warning btn-sm rounded-pill complaint-btn" data-type="post" data-id="{{ $post->id }}">Пожаловаться</button>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm rounded-pill">❤️ {{ $post->likes_count ?? 0 }}</a>
            @endauth
        </div>

        <h3>Комментарии</h3>
        @foreach($post->comments->where('parent_id', null) as $comment)
            @include('forum._comment', ['comment' => $comment])
        @endforeach

        @auth
            <div class="card shadow-sm border-0 rounded-3 mt-4">
                <div class="card-body p-4">
                    <h5>Добавить комментарий</h5>
                    <form method="POST" action="{{ route('comment.store') }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="3" required placeholder="Ваш комментарий"></textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Отправить</button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4 rounded-3">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>.</div>
        @endauth
    </div>

    <script>
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = this.dataset.type;
                const id = this.dataset.id;
                fetch('{{ route("like.toggle") }}', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}','Content-Type': 'application/json'},
                    body: JSON.stringify({ type: type, id: id })
                })
                    .then(response => response.json())
                    .then(data => {
                        this.classList.toggle('btn-outline-danger');
                        this.classList.toggle('btn-danger');
                        this.querySelector('.likes-count').innerText = data.likesCount;
                    });
            });
        });
        // кнопка жалобы (как ранее)
        document.querySelectorAll('.complaint-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // аналогично – открытие модального окна
            });
        });
    </script>
@endsection
