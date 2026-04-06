<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'church_name',
        'church_abbr',
        'type',
        'logo',
        'profile_picture',
        'contact_number',
        'describe',
        'public_link',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function churches()
    {
        return $this->belongsToMany(Church::class)
            ->withPivot('is_approved', 'type')
            ->withTimestamps();
    }

    public function isAdmin($churchId)
    {
        $church = $this->churches()
            ->where('church_id', $churchId)
            ->first();

        return $church && strtolower($church->pivot->type ?? '') === 'admin';
    }

    public function isUser($churchId)
    {
        $church = $this->churches()
            ->where('church_id', $churchId)
            ->first();

        return $church && strtolower($church->pivot->type ?? '') === 'member';
    }

    public function roles()
    {
        return $this->belongsToMany(
            \App\Models\RoleSelect::class,
            'role_user'
        );
    }


    public function socialLinks()
    {
        return $this->hasMany(SocialUser::class);
    }

    public function songs()
    {
        return $this->hasMany(\App\Models\SongUser::class);
    }
}
