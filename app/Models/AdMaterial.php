<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdMaterial extends Model
{
    protected $fillable = ['name','type','content','target_url','placement_key','weight','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function stats()
    {
        return $this->hasMany(AdStat::class);
    }
}
