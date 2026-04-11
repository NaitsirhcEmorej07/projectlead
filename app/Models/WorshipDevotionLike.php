<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorshipDevotionLike extends Model
{
    protected $fillable = [
        'worship_devotion_id',
        'user_id',
        'church_id',
        'reaction'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
