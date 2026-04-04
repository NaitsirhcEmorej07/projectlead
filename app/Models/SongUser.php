<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongUser extends Model
{
protected $table = 'song_user';

    protected $fillable = [
        'user_id',
        'church_id',
        'song_title',
        'song_by',
        'song_reference',
        'user_key',
    ];
}
