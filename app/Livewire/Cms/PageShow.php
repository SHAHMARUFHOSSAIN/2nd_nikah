<?php

namespace App\Livewire\Cms;

use App\Models\CmsPage;
use Livewire\Component;

class PageShow extends Component
{
    public CmsPage $page;

    public function mount(string $slug): void
    {
        $cmsPage = CmsPage::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (! $cmsPage) {
            abort(404);
        }

        $this->page = $cmsPage;
    }

    public function render()
    {
        return view('livewire.cms.page-show')
            ->layout('layouts.app', ['title' => $this->page->title]);
    }
}
