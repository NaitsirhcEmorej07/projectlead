<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $fillable = [
        'name',
        'abbr',
        'created_by',
        'logo',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_approved', 'type')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
