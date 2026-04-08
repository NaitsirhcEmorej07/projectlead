<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'church_id',
        'user_id',
        'sched_title',
        'sched_description',
        'sched_type',
        'sched_date',
        'sched_time',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 👉 schedule belongs to a church
    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    // 👉 schedule belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}