<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $fillable = [
        'name',
        'abbr',
        'created_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
