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
     * Profile photos gallery relationship.
     */
    public function profilePhotos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProfilePhoto::class)->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Primary profile photo relationship.
     */
    public function primaryProfilePhoto(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProfilePhoto::class)->where('is_primary', true);
    }

    /**
     * Ensure primary photo is synchronized to member_profiles.profile_photo_path.
     */
    public function syncPrimaryPhotoToMemberProfile(): void
    {
        $profile = $this->memberProfile;
        if (! $profile) {
            return;
        }

        $primary = $this->profilePhotos()->where('is_primary', true)->first();
        if (! $primary) {
            $primary = $this->profilePhotos()->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->first();
            if ($primary) {
                $this->profilePhotos()->update(['is_primary' => false]);
                $primary->update(['is_primary' => true]);
            }
        }

        $newPath = $primary ? $primary->path : null;
        if ($profile->profile_photo_path !== $newPath) {
            $profile->update(['profile_photo_path' => $newPath]);
            $profile->syncCompletionStatus();
        }
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

    /**
     * Subscriptions relationship.
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Payment transactions relationship.
     */
    public function paymentTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Get current active subscription if any.
     */
    public function activeSubscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->whereNotNull('starts_at')
            ->whereNotNull('ends_at')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->latestOfMany('id');
    }

    /**
     * Check if user currently has an active Premium membership.
     */
    public function isPremium(): bool
    {
        return $this->activeSubscription !== null;
    }

    /**
     * Get total count of unread messages for this user across all conversations.
     */
    public function unreadMessagesCount(): int
    {
        return Message::whereHas('conversation', function ($q) {
            $q->where('user_one_id', $this->id)
              ->orWhere('user_two_id', $this->id);
        })
        ->where('sender_id', '!=', $this->id)
        ->whereNull('read_at')
        ->count();
    }

    public function blocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Block::class, 'blocker_id');
    }

    public function blockedBy(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Block::class, 'blocked_id');
    }

    public function hasBlocked(int $userId): bool
    {
        return Block::where('blocker_id', $this->id)->where('blocked_id', $userId)->exists();
    }

    public function isBlockedBy(int $userId): bool
    {
        return Block::where('blocker_id', $userId)->where('blocked_id', $this->id)->exists();
    }

    public function hasBlockedOrIsBlockedBy(int $userId): bool
    {
        return Block::where(function ($q) use ($userId) {
            $q->where('blocker_id', $this->id)->where('blocked_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('blocker_id', $userId)->where('blocked_id', $this->id);
        })->exists();
    }

    public function getBlockedUserIds(): array
    {
        $blocked = Block::where('blocker_id', $this->id)->pluck('blocked_id')->toArray();
        $blockedBy = Block::where('blocked_id', $this->id)->pluck('blocker_id')->toArray();

        return array_unique(array_merge($blocked, $blockedBy));
    }

    public function shortlists(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Shortlist::class, 'user_id');
    }

    public function hasShortlisted(int $userId): bool
    {
        return Shortlist::where('user_id', $this->id)->where('shortlisted_user_id', $userId)->exists();
    }

    public function profileVisits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProfileVisit::class, 'visitor_id');
    }

    public function receivedVisits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProfileVisit::class, 'visited_user_id');
    }

    public function reportsSent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function reportsReceived(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    public function whatsappRequestsSent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WhatsappShareRequest::class, 'requester_id');
    }

    public function whatsappRequestsReceived(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WhatsappShareRequest::class, 'receiver_id');
    }
}
