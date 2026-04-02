<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongSelect extends Model
{
    protected $table = 'song_select';

    protected $fillable = [
        'church_id',
        'song_title',
        'song_by',
        'song_reference',
        'original_key',
    ];
}
