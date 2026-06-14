<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    /**
     * Связь "многие ко многим" с моделью Post
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
