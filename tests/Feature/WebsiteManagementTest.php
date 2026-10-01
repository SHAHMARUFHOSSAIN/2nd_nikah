<?php

namespace Tests\Feature;

use App\Filament\Pages\WebsiteManagement;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SettingSeeder::class);
    }

    private function createAdminUser(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    private function createRegularUser(): User
    {
        return User::factory()->create([
            'is_admin' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function admin_can_access_website_management_page(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/admin/website-management');

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_website_management_page(): void
    {
        $user = $this->createRegularUser();

        $response = $this->actingAs($user)->get('/admin/website-management');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_save_branding_and_hero_settings(): void
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin);

        Livewire::test(WebsiteManagement::class)
            ->set('data.site_name', '2nd Nikah Official')
            ->set('data.site_tagline', 'Blessed Second Chapter')
            ->set('data.hero_heading', 'Find Your Blessed Life Partner')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('2nd Nikah Official', Setting::get('site_name'));
        $this->assertEquals('Blessed Second Chapter', Setting::get('site_tagline'));
        $this->assertEquals('Find Your Blessed Life Partner', Setting::get('hero_heading'));
    }

    /** @test */
    public function frontend_renders_updated_cms_settings_dynamically(): void
    {
        Setting::set('site_name', '2nd Nikah Platform');
        Setting::set('hero_eyebrow', 'SERIOUS MATRIMONIAL');
        Setting::set('hero_heading', 'Dignified Second Marriage');

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('2nd Nikah Platform');
        $response->assertSee('SERIOUS MATRIMONIAL');
        $response->assertSee('Dignified Second Marriage');
    }

    /** @test */
    public function app_download_urls_sync_correctly(): void
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin);

        Livewire::test(WebsiteManagement::class)
            ->set('data.app_download_google_play_url', 'https://play.google.com/store/apps/details?id=com.custom.app')
            ->set('data.app_download_apple_store_url', 'https://apps.apple.com/app/custom-id123')
            ->call('save');

        $this->assertEquals('https://play.google.com/store/apps/details?id=com.custom.app', Setting::get('play_store_url'));
        $this->assertEquals('https://apps.apple.com/app/custom-id123', Setting::get('app_store_url'));
    }

    /** @test */
    public function setting_get_asset_url_returns_valid_url_for_existing_uploaded_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-logo.png');
        $path = $file->storeAs('site/branding', 'test-logo.png', 'public');

        Setting::set('logo_path', $path);

        $url = Setting::getAssetUrl('logo_path');

        $this->assertNotNull($url);
        $this->assertStringContainsString('site/branding/test-logo.png', $url);
    }

    /** @test */
    public function setting_get_asset_url_returns_null_if_physical_file_is_missing(): void
    {
        Storage::fake('public');

        Setting::set('logo_path', 'site/branding/non-existent-logo.png');

        $url = Setting::getAssetUrl('logo_path');

        $this->assertNull($url);
    }

    /** @test */
    public function frontend_header_renders_cms_uploaded_logo_when_file_exists(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('custom-logo.png');
        $path = $file->storeAs('site/branding', 'custom-logo.png', 'public');
        Setting::set('logo_path', $path);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('custom-logo.png');
    }

    /** @test */
    public function frontend_header_falls_back_to_text_brand_when_logo_file_is_missing(): void
    {
        Storage::fake('public');

        Setting::set('logo_path', 'site/branding/missing.png');

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('brand-icon');
        $response->assertDontSee('missing.png');
    }

    /** @test */
    public function frontend_footer_uses_footer_logo_or_falls_back_to_main_logo_or_text(): void
    {
        Storage::fake('public');

        // Case A: Main logo exists, footer logo does not -> Footer uses main logo
        $mainFile = UploadedFile::fake()->image('main-logo.png');
        $mainPath = $mainFile->storeAs('site/branding', 'main-logo.png', 'public');
        Setting::set('logo_path', $mainPath);
        Setting::set('footer_logo_path', null);

        $responseA = $this->get(route('home'));
        $responseA->assertStatus(200);
        $responseA->assertSee('main-logo.png');

        // Case B: Footer logo exists -> Footer uses footer logo
        $footerFile = UploadedFile::fake()->image('footer-logo.png');
        $footerPath = $footerFile->storeAs('site/footer', 'footer-logo.png', 'public');
        Setting::set('footer_logo_path', $footerPath);

        $responseB = $this->get(route('home'));
        $responseB->assertStatus(200);
        $responseB->assertSee('footer-logo.png');

        // Case C: Neither exists -> Text fallback
        Setting::set('logo_path', null);
        Setting::set('footer_logo_path', null);

        $responseC = $this->get(route('home'));
        $responseC->assertStatus(200);
        $responseC->assertSee('2nd Nikah');
    }

    /** @test */
    public function favicon_renders_dynamically_in_layout(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('favicon.png');
        $path = $file->storeAs('site/branding', 'favicon.png', 'public');
        Setting::set('favicon_path', $path);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('rel="icon"', false);
        $response->assertSee('favicon.png');
    }
}
