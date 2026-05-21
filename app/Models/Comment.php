<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'commentable_id', 'commentable_type', 'content', 'parent_id', 'likes_count','is_hidden'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedByUser()
    {
        if (!auth()->check()) return false;
        return \App\Models\Like::where('user_id', auth()->id())
            ->where('likeable_id', $this->id)
            ->where('likeable_type', 'App\Models\Comment')
            ->exists();
    }
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
