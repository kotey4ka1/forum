<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchQuery extends Model
{
    protected $fillable = ['user_id', 'query', 'results_count'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
