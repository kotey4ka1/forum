<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'commentable_id', 'commentable_type', 'content', 'parent_id', 'likes_count', 'is_hidden'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Автор
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Полиморфная связь
    public function commentable()
    {
        return $this->morphTo();
    }

    // Родительский комментарий (для вложенности)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Ответы на комментарий
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Лайки комментария
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Проверка, лайкнул ли текущий пользователь
    public function isLikedByUser()
    {
        if (!auth()->check()) return false;
        return Like::where('user_id', auth()->id())
            ->where('likeable_id', $this->id)
            ->where('likeable_type', 'App\Models\Comment')
            ->exists();
    }

    // Scope для видимых (не скрытых) комментариев (если используется is_hidden)
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    // Связь с постом (для комментариев, привязанных к посту – полезно для быстрого доступа)
    public function post()
    {
        // Если комментарий относится к посту, то commentable_type = 'App\Models\Post'
        if ($this->commentable_type === 'App\Models\Post') {
            return $this->belongsTo(Post::class, 'commentable_id');
        }
        return null;
    }
}
