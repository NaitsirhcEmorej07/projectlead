<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorshipDevotionImage extends Model
{
    protected $table = 'worship_devotion_images';

    protected $fillable = [
        'worship_devotion_id',
        'user_id',
        'church_id',
        'image_path',
    ];

    /*
    |------------------------------------------
    | RELATIONSHIPS
    |------------------------------------------
    */

    // 🔥 belongs to devotion
    public function devotion()
    {
        return $this->belongsTo(WorshipDevotion::class, 'worship_devotion_id');
    }

    // 🔥 belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 belongs to church
    public function church()
    {
        return $this->belongsTo(Church::class);
    }
}
