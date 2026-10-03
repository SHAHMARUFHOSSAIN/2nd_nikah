<?php

namespace App\Livewire\Cms;

use App\Models\CmsPage;
use Livewire\Component;

class PageShow extends Component
{
    public CmsPage $page;

    public function mount(string $slug): void
    {
        $aliases = [
            'terms' => 'terms-and-conditions',
            'terms-and-condition' => 'terms-and-conditions',
            'terms-of-service' => 'terms-and-conditions',
            'privacy' => 'privacy-policy',
            'refund' => 'refund-policy',
            'return-and-refund' => 'refund-policy',
            'return-and-refund-policy' => 'refund-policy',
            'refund-and-return-policy' => 'refund-policy',
            'about' => 'about-us',
        ];

        $targetSlug = $aliases[strtolower(trim($slug))] ?? $slug;

        $cmsPage = CmsPage::where('slug', $targetSlug)
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
