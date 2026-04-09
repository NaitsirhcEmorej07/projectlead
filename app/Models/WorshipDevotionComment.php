<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorshipDevotionComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'worship_devotion_id',
        'user_id',
        'church_id',
        'comment',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function devotion()
    {
        return $this->belongsTo(WorshipDevotion::class, 'worship_devotion_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Parent comment
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Replies
    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
