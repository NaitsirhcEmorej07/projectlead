<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleSelect extends Model
{
    protected $table = 'role_select';

    protected $fillable = [
        'role_name',
        'role_slug',
    ];

    // 🔥 Relationship to users (pivot)
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'role_user'
        );
    }
}