<?php

namespace App\Livewire\Members;

use App\Models\MemberProfile;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $members = MemberProfile::discoverable()
            ->with('user')
            ->latest()
            ->paginate(12);

        return view('livewire.members.index', [
            'members' => $members,
        ])->layout('components.layouts.app', ['title' => 'Discover Members']);
    }
}
