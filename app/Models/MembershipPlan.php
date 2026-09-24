<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'country_scope',
        'amount',
        'currency',
        'billing_interval',
        'duration_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'duration_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeBd(Builder $query): Builder
    {
        return $query->where('country_scope', 'BD');
    }

    public function scopeInternational(Builder $query): Builder
    {
        return $query->where('country_scope', 'INTERNATIONAL');
    }

    public function scopeForCountry(Builder $query, ?string $country): Builder
    {
        $normalized = strtoupper(trim((string) $country));
        if ($normalized === 'BD' || $normalized === 'BANGLADESH') {
            return $query->where('country_scope', 'BD');
        }

        return $query->where('country_scope', 'INTERNATIONAL');
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->currency === 'BDT') {
            return '৳' . number_format($this->amount, 0);
        }

        return '$' . number_format($this->amount, 0);
    }
}
