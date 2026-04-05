<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialUser extends Model
{
    protected $table = 'social_user';

    protected $fillable = [
        'user_id',
        'social_platform',
        'social_link',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
