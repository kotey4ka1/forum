<div class="card mb-2 shadow-sm" id="comment-{{ $comment->id }}">
    <div class="card-body">
        @if($comment->parent_id)
            <div class="small text-muted mb-2">
                <i class="bi bi-reply-fill"></i> в ответ на
                <a href="#comment-{{ $comment->parent_id }}" class="text-decoration-none">
                    {{ $comment->parent->user->name ?? 'удалён' }}
                </a>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <strong class="me-2">{{ $comment->user->name ?? 'Гость' }}</strong>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                @auth
                    <button class="btn btn-link text-secondary text-decoration-none reply-btn"
                            data-comment-id="{{ $comment->id }}"
                            data-username="{{ $comment->user->name }}">
                        Ответить
                    </button>
                    @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin() || Auth::user()->isModerator())
                        <button class="btn btn-link text-secondary text-decoration-none edit-comment-btn"
                                data-id="{{ $comment->id }}">
                            Редактировать
                        </button>
                        <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none"
                                    onclick="return confirm('Удалить комментарий?')">
                                Удалить
                            </button>
                        </form>
                    @endif
                    <!-- Кнопка жалобы на комментарий -->
                    <button class="btn btn-link text-warning text-decoration-none complaint-btn"
                            data-type="comment" data-id="{{ $comment->id }}">
                        Пожаловаться
                    </button>
                @endauth
            </div>
        </div>

        <div class="comment-content mt-2 mb-2" id="comment-content-{{ $comment->id }}">
            <p class="mb-0">{{ $comment->content }}</p>
        </div>

        <div class="mt-2">
            @auth
                <button class="btn btn-sm rounded-pill px-3 like-btn {{ $comment->isLikedByUser() ? 'btn-danger' : 'btn-outline-danger' }}"
                        data-type="comment" data-id="{{ $comment->id }}">
                    <i class="bi bi-heart"></i> <span class="likes-count">{{ $comment->likes_count }}</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    ❤️ {{ $comment->likes_count }}
                </a>
            @endauth
        </div>

        <div class="reply-form mt-3" id="reply-form-{{ $comment->id }}" style="display: none;">
            <form method="POST" action="{{ route('comment.store') }}">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="mb-2">
                    <textarea class="form-control form-control-sm" name="content" rows="2" required placeholder="Ваш ответ..."></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary">Отправить ответ</button>
                    <button type="button" class="btn btn-sm btn-secondary cancel-reply">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($comment->replies && $comment->replies->count())
    <div class="ms-4">
        @foreach($comment->replies as $reply)
            @include('forum._comment', ['comment' => $reply])
        @endforeach
    </div>
@endif
