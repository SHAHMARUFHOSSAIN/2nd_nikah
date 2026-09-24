<?php

namespace App\Livewire\Membership;

use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url]
    public string $country = 'Bangladesh';

    public function mount(): void
    {
        if (Auth::check() && Auth::user()->memberProfile?->country) {
            $userCountry = Auth::user()->memberProfile->country;
            if (trim($userCountry) !== '') {
                $this->country = $userCountry;
            }
        }
    }

    public function selectPlan(string $planSlug): void
    {
        $this->redirect(route('membership.checkout', [
            'plan' => $planSlug,
            'country' => $this->country,
        ]));
    }

    public function render()
    {
        $isBd = in_array(strtoupper(trim($this->country)), ['BD', 'BANGLADESH']);

        $plans = MembershipPlan::active()
            ->forCountry($this->country)
            ->get();

        return view('livewire.membership.index', [
            'plans' => $plans,
            'isBd' => $isBd,
        ])->layout('components.layouts.app', ['title' => 'Membership Plans']);
    }
}
