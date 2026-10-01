<div class="container" style="max-width: 900px; margin: 3rem auto;">
    
    <div class="card" style="border-radius: 1.5rem; padding: 2.5rem; background: #FFFFFF; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <h1 style="font-size: 2.25rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 1rem; border-bottom: 2px solid var(--border-warm); padding-bottom: 1rem;">
            {{ $page->title }}
        </h1>

        <div class="prose" style="color: var(--text-main); font-size: 1.05rem; line-height: 1.8;">
            {!! $page->content !!}
        </div>
    </div>

</div>
