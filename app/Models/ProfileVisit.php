<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'visited_user_id',
        'last_visited_at',
    ];

    protected function casts(): array
    {
        return [
            'last_visited_at' => 'datetime',
        ];
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visitor_id');
    }

    public function visitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visited_user_id');
    }

    public static function recordVisit(int $visitorId, int $visitedUserId): ?self
    {
        if ($visitorId === $visitedUserId) {
            return null;
        }

        return static::updateOrCreate(
            [
                'visitor_id' => $visitorId,
                'visited_user_id' => $visitedUserId,
            ],
            [
                'last_visited_at' => now(),
            ]
        );
    }
}
