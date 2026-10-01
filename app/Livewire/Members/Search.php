<?php

namespace App\Livewire\Members;

use App\Livewire\Concerns\HandlesMemberCardActions;
use App\Models\MemberProfile;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;
    use HandlesMemberCardActions;

    #[Url(except: '')]
    public string $gender = '';

    #[Url(except: '')]
    public string $min_age = '';

    #[Url(except: '')]
    public string $max_age = '';

    #[Url(except: '')]
    public string $religion = '';

    #[Url(except: '')]
    public string $marital_status = '';

    #[Url(except: '')]
    public string $city = '';

    #[Url(except: '')]
    public string $country = '';

    #[Url(except: '')]
    public string $education = '';

    #[Url(except: '')]
    public string $children = '';

    #[Url(except: '')]
    public string $min_height = '';

    #[Url(except: '')]
    public string $max_height = '';

    #[Url(except: 'newest')]
    public string $sort = 'newest';

    /**
     * Reset pagination when any filter changes.
     */
    public function updated($property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    /**
     * Compute count of active filters.
     */
    public function getActiveFilterCountProperty(): int
    {
        $count = 0;
        if ($this->gender !== '') $count++;
        if ($this->min_age !== '') $count++;
        if ($this->max_age !== '') $count++;
        if ($this->religion !== '') $count++;
        if ($this->marital_status !== '') $count++;
        if ($this->city !== '') $count++;
        if ($this->country !== '') $count++;
        if ($this->education !== '') $count++;
        if ($this->children !== '') $count++;
        if ($this->min_height !== '') $count++;
        if ($this->max_height !== '') $count++;
        return $count;
    }

    /**
     * Remove a single filter property.
     */
    public function removeFilter(string $property): void
    {
        if (property_exists($this, $property)) {
            $this->{$property} = '';
            $this->resetPage();
        }
    }

    /**
     * Reset all search filters.
     */
    public function clearFilters(): void
    {
        $this->gender = '';
        $this->min_age = '';
        $this->max_age = '';
        $this->religion = '';
        $this->marital_status = '';
        $this->city = '';
        $this->country = '';
        $this->education = '';
        $this->children = '';
        $this->min_height = '';
        $this->max_height = '';
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function render()
    {
        $query = MemberProfile::query()
            ->discoverable()
            ->with('user');

        if (auth()->check()) {
            $blockedIds = auth()->user()->getBlockedUserIds();
            if (! empty($blockedIds)) {
                $query->whereNotIn('user_id', $blockedIds);
            }
        }

        // Gender Filter
        if ($this->gender !== '') {
            $query->where('gender', $this->gender);
        }

        // Min Age Filter (Minimum age implies date_of_birth <= now - min_age years)
        if ($this->min_age !== '' && is_numeric($this->min_age)) {
            $minAgeDate = now()->subYears((int) $this->min_age)->endOfDay();
            $query->where('date_of_birth', '<=', $minAgeDate);
        }

        // Max Age Filter (Maximum age implies date_of_birth >= now - (max_age + 1) years + 1 day)
        if ($this->max_age !== '' && is_numeric($this->max_age)) {
            $maxAgeDate = now()->subYears((int) $this->max_age + 1)->addDay()->startOfDay();
            $query->where('date_of_birth', '>=', $maxAgeDate);
        }

        // Religion Filter
        if ($this->religion !== '') {
            $query->where('religion', $this->religion);
        }

        // Marital Status Filter
        if ($this->marital_status !== '') {
            $query->where('marital_status', $this->marital_status);
        }

        // City Filter (Partial match)
        if ($this->city !== '') {
            $query->where('city', 'like', '%' . trim($this->city) . '%');
        }

        // Country Filter (Partial match)
        if ($this->country !== '') {
            $query->where('country', 'like', '%' . trim($this->country) . '%');
        }

        // Education Filter (Partial match)
        if ($this->education !== '') {
            $query->where('education', 'like', '%' . trim($this->education) . '%');
        }

        // Children Filter
        if ($this->children === 'no') {
            $query->where(function (Builder $q) {
                $q->whereNull('children_count')
                  ->orWhere('children_count', 0);
            });
        } elseif ($this->children === 'yes') {
            $query->where('children_count', '>', 0);
        }

        // Min Height Filter (cm)
        if ($this->min_height !== '' && is_numeric($this->min_height)) {
            $query->where('height', '>=', (int) $this->min_height);
        }

        // Max Height Filter (cm)
        if ($this->max_height !== '' && is_numeric($this->max_height)) {
            $query->where('height', '<=', (int) $this->max_height);
        }

        // Sorting
        match ($this->sort) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'youngest' => $query->orderBy('date_of_birth', 'desc'),
            'oldest_age' => $query->orderBy('date_of_birth', 'asc'),
            'height_asc' => $query->orderBy('height', 'asc'),
            'height_desc' => $query->orderBy('height', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $profiles = $query->paginate(12);

        return view('livewire.members.search', [
            'profiles' => $profiles,
        ])->layout('components.layouts.app', ['title' => 'Search & Discover Members']);
    }
}
