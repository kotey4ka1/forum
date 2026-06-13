<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $table = 'support_requests';
    protected $fillable = ['user_id', 'subject', 'type', 'content', 'status', 'response', 'assigned_moderator_id', 'responded_at', 'closed_at'];

    protected $casts = [
        'responded_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'assigned_moderator_id');
    }

    public function getTypeNameAttribute()
    {
        return [
            'payment' => 'Оплата',
            'consultation' => 'Консультация',
            'complaint' => 'Жалоба',
            'other' => 'Другое',
        ][$this->type] ?? $this->type;
    }

    public function getStatusNameAttribute()
    {
        return [
            'new' => 'Новое',
            'in_progress' => 'В работе',
            'closed' => 'Закрыто',
        ][$this->status] ?? $this->status;
    }
}
