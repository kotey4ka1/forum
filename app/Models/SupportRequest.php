<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $table = 'support_requests';
    protected $fillable = [
        'user_id', 'subject', 'type', 'content', 'status',
        'assigned_moderator_id', 'response', 'responded_at', 'closed_at'
    ];

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
}
