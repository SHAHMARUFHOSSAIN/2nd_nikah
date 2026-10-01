<?php

namespace App\Livewire\Members;

use App\Livewire\Concerns\HandlesMemberCardActions;
use App\Models\MemberProfile;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use HandlesMemberCardActions;

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $query = MemberProfile::discoverable()->with('user');

        if (auth()->check()) {
            $blockedIds = auth()->user()->getBlockedUserIds();
            if (! empty($blockedIds)) {
                $query->whereNotIn('user_id', $blockedIds);
            }
        }

        $members = $query->latest()->paginate(12);

        return view('livewire.members.index', [
            'members' => $members,
        ])->layout('components.layouts.app', ['title' => 'Discover Members']);
    }
}
