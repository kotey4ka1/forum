<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'role_id', 'is_banned', 'last_seen_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_banned' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Post::class, 'favorites');
    }

    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isModerator()
    {
        return $this->role && $this->role->name === 'moderator';
    }

    // Стандартный сброс пароля (через форму забыли пароль) – использует стандартное уведомление Laravel
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }

    // Специальный метод для смены пароля из профиля (отправляет кастомное уведомление с меткой source=profile)
    public function sendPasswordResetNotificationFromProfile($token)
    {
        $this->notify(new CustomResetPassword($token, $this->email, 'profile'));
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
}
