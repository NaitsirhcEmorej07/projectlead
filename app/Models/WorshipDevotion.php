<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorshipDevotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'church_id',
        'content',
        'likes_count',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function comments()
    {
        return $this->hasMany(WorshipDevotionComment::class);
    }

    public function likes()
    {
        return $this->hasMany(WorshipDevotionLike::class, 'worship_devotion_id');
    }
}
