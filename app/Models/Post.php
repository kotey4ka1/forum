<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'forum_section_id', 'title', 'content', 'views_count', 'is_pinned', 'likes_count','is_hidden'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(ForumSection::class, 'forum_section_id');
    }

    // Полиморфные комментарии к посту
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Полиморфные лайки поста
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function images()
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
