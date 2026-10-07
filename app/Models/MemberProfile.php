<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MemberProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'marital_status',
        'religion',
        'location',
        'city',
        'country',
        'phone',
        'height',
        'education',
        'occupation',
        'about_me',
        'partner_expectation',
        'children_count',
        'profile_photo_path',
        'is_profile_complete',
        'is_profile_visible',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'height' => 'integer',
            'children_count' => 'integer',
            'is_profile_complete' => 'boolean',
            'is_profile_visible' => 'boolean',
        ];
    }

    /**
     * Relationship to the owning User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full name helper attribute.
     */
    public function getFullNameAttribute(): string
    {
        $name = trim("{$this->first_name} {$this->last_name}");
        return $name ?: ($this->user->name ?? 'Member');
    }

    /**
     * Scope query to only include discoverable member profiles.
     */
    public function scopeDiscoverable($query)
    {
        return $query->where('is_profile_visible', true)
            ->whereHas('user', function ($q) {
                $q->whereNotNull('email_verified_at')
                  ->where('is_active', true)
                  ->where('is_admin', false);
            });
    }

    /**
     * Get calculated age from date_of_birth.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Get display marital status helper attribute (replaces "Never Married" with "Unmarried").
     */
    public function getDisplayMaritalStatusAttribute(): string
    {
        $status = $this->marital_status;
        if ($status === 'Never Married') {
            return 'Unmarried';
        }

        return $status ?: 'Not specified';
    }

    /**
     * Get formatted height helper in feet & inches (e.g., 5' 6").
     */
    public function getFormattedHeightAttribute(): ?string
    {
        if (! $this->height) {
            return null;
        }

        $val = (int) $this->height;
        $totalInches = $val >= 100 ? (int) round($val / 2.54) : $val;
        $feet = (int) floor($totalInches / 12);
        $inches = $totalInches % 12;

        return "{$feet}' {$inches}\"";
    }

    /**
     * Get photo URL helper.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return null;
    }

    /**
     * List of required fields for 100% completion.
     */
    public static function getRequiredFields(): array
    {
        return [
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'marital_status',
            'religion',
            'city',
            'country',
            'phone',
            'height',
            'education',
            'occupation',
            'about_me',
        ];
    }

    /**
     * Calculate profile completion percentage dynamically from database attributes.
     */
    public function calculateCompletionPercentage(): int
    {
        $requiredFields = static::getRequiredFields();
        $filledCount = 0;

        foreach ($requiredFields as $field) {
            $val = $this->{$field};
            if (! is_null($val) && trim((string) $val) !== '') {
                $filledCount++;
            }
        }

        $percentage = (int) round(($filledCount / count($requiredFields)) * 100);

        return min(100, max(0, $percentage));
    }

    /**
     * Update and persist completion status in database.
     */
    public function syncCompletionStatus(): void
    {
        $percentage = $this->calculateCompletionPercentage();
        $isComplete = ($percentage >= 100);

        if ($this->is_profile_complete !== $isComplete) {
            $this->is_profile_complete = $isComplete;
            $this->saveQuietly();
        }
    }
}
