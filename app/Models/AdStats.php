<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdStats extends Model
{
    protected $table = 'ad_stats';
    protected $fillable = ['material_id', 'user_id', 'event_type', 'ip_address'];
    public $timestamps = false; // потому что используем created_at

    public function material()
    {
        return $this->belongsTo(AdMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
