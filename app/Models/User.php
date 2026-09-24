<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
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
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin && (bool) $this->is_active;
    }

    /**
     * Relationship to MemberProfile.
     */
    public function memberProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    /**
     * Sent interests relationship.
     */
    public function sentInterests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Interest::class, 'sender_id');
    }

    /**
     * Received interests relationship.
     */
    public function receivedInterests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Interest::class, 'receiver_id');
    }

    /**
     * Matches as User One.
     */
    public function matchesAsUserOne(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserMatch::class, 'user_one_id');
    }

    /**
     * Matches as User Two.
     */
    public function matchesAsUserTwo(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserMatch::class, 'user_two_id');
    }
}
