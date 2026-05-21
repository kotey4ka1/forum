<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id', 'complaintable_id', 'complaintable_type', 'reason',
        'status', 'moderator_id', 'moderator_comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complaintable()
    {
        return $this->morphTo();
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
}
