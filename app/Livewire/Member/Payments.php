<?php

namespace App\Livewire\Member;

use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component
{
    use WithPagination;

    public function render()
    {
        $transactions = PaymentTransaction::with(['membershipPlan', 'subscription'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.member.payments', [
            'transactions' => $transactions,
        ])->layout('components.layouts.app', ['title' => 'My Payment History']);
    }
}
