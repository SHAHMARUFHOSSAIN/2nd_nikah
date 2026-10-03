<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class WebsiteManagement extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'CONTENT';

    protected static ?string $navigationLabel = 'Website Management';

    protected static ?string $title = 'Website Management';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.website-management';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // Ensure key synonyms are populated
        if (! isset($settings['app_download_google_play_url']) && isset($settings['play_store_url'])) {
            $settings['app_download_google_play_url'] = $settings['play_store_url'];
        }
        if (! isset($settings['app_download_apple_store_url']) && isset($settings['app_store_url'])) {
            $settings['app_download_apple_store_url'] = $settings['app_store_url'];
        }

        // Set defaults for Hero App Download options if not set
        if (! isset($settings['hero_app_download_enabled'])) {
            $settings['hero_app_download_enabled'] = true;
        }
        if (! isset($settings['hero_app_download_title'])) {
            $settings['hero_app_download_title'] = 'Get The Official Mobile App';
        }
        if (! isset($settings['hero_app_download_subtitle'])) {
            $settings['hero_app_download_subtitle'] = 'Instant match notifications, private biometric chat & real-time updates on Android & iOS.';
        }
        if (! isset($settings['app_download_google_play_enabled'])) {
            $settings['app_download_google_play_enabled'] = true;
        }
        if (! isset($settings['app_download_apple_store_enabled'])) {
            $settings['app_download_apple_store_enabled'] = true;
        }

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Website Management')
                    ->tabs([
                        Tabs\Tab::make('Branding')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('Site Identity')
                                    ->description('Manage website title, tagline, and brand identity')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Site Name')
                                            ->required(),
                                        TextInput::make('site_tagline')
                                            ->label('Site Tagline')
                                            ->required(),
                                    ])->columns(2),

                                Section::make('Logos & Media Assets')
                                    ->description('Upload website logo and browser favicon directly from your computer')
                                    ->schema([
                                        FileUpload::make('logo_path')
                                            ->label('Website Logo')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/branding')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->imagePreviewHeight('100')
                                            ->helperText('PNG, JPG, WEBP formats. Max: 10MB. Appears in website header.'),
                                        FileUpload::make('favicon_path')
                                            ->label('Browser Favicon')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/branding')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->imagePreviewHeight('50')
                                            ->helperText('PNG, ICO, WEBP formats. Max: 10MB. Appears in browser tab.'),
                                    ])->columns(2),

                                Section::make('Theme Colors')
                                    ->description('Customize website brand primary and accent colors')
                                    ->schema([
                                        ColorPicker::make('primary_color')
                                            ->label('Primary Brand Color')
                                            ->default('#E11D48'),
                                        ColorPicker::make('secondary_color')
                                            ->label('Secondary Accent Color')
                                            ->default('#F472B6'),
                                        ColorPicker::make('accent_color')
                                            ->label('Soft Accent Color')
                                            ->default('#FB7185'),
                                    ])->columns(3),
                            ]),

                        Tabs\Tab::make('Homepage')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Section::make('Hero Banner')
                                    ->description('Manage homepage main hero section text and desktop banner image')
                                    ->schema([
                                        Toggle::make('hero_enabled')
                                            ->label('Enable Hero Section')
                                            ->default(true),
                                        TextInput::make('hero_eyebrow')
                                            ->label('Hero Eyebrow Badge Text')
                                            ->default('MATRIMONIAL PLATFORM'),
                                        TextInput::make('hero_heading')
                                            ->label('Hero Main Heading')
                                            ->required()
                                            ->columnSpanFull(),
                                        Textarea::make('hero_subtitle')
                                            ->label('Hero Subtitle / Description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Grid::make(2)->schema([
                                            TextInput::make('hero_cta_primary_text')->label('Primary Button Text')->default('Browse Verified Members'),
                                            TextInput::make('hero_cta_primary_url')->label('Primary Button URL')->default('/members'),
                                            TextInput::make('hero_cta_secondary_text')->label('Secondary Button Text')->default('Go to Dashboard'),
                                            TextInput::make('hero_cta_secondary_url')->label('Secondary Button URL')->default('/dashboard'),
                                        ]),
                                        FileUpload::make('hero_image')
                                            ->label('Hero Desktop Banner Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/homepage')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->columnSpanFull()
                                            ->helperText('Direct file upload for Desktop Hero banner image. Max: 10MB. Overrides default gradient.'),
                                    ]),

                                Section::make('Hero App Download Bar (Under Hero Section)')
                                    ->description('Manage the Google Play and Apple App Store buttons displayed right beneath the Hero section. Activate, inactivate, or customize links anytime.')
                                    ->schema([
                                        Toggle::make('hero_app_download_enabled')
                                            ->label('Show App Download Bar below Hero')
                                            ->helperText('Master Switch: Toggle ON to show or OFF to hide the app download option below the hero section.')
                                            ->default(true),

                                        TextInput::make('hero_app_download_title')
                                            ->label('App Bar Heading')
                                            ->default('Get The Official Mobile App')
                                            ->placeholder('e.g. Get The Official Mobile App'),

                                        TextInput::make('hero_app_download_subtitle')
                                            ->label('App Bar Subtitle')
                                            ->default('Instant match notifications, private biometric chat & real-time updates on Android & iOS.')
                                            ->columnSpanFull(),

                                        Grid::make(2)->schema([
                                            Section::make('Google Play Store Option')
                                                ->schema([
                                                    Toggle::make('app_download_google_play_enabled')
                                                        ->label('Google Play Button Active')
                                                        ->helperText('Activate or Inactivate Google Play Store button')
                                                        ->default(true),
                                                    TextInput::make('app_download_google_play_url')
                                                        ->label('Google Play Store URL')
                                                        ->placeholder('https://play.google.com/store/apps/details?id=...'),
                                                ]),

                                            Section::make('Apple App Store Option')
                                                ->schema([
                                                    Toggle::make('app_download_apple_store_enabled')
                                                        ->label('Apple App Store Button Active')
                                                        ->helperText('Activate or Inactivate Apple App Store button')
                                                        ->default(true),
                                                    TextInput::make('app_download_apple_store_url')
                                                        ->label('Apple App Store URL')
                                                        ->placeholder('https://apps.apple.com/app/...'),
                                                ]),
                                        ]),
                                    ]),

                                Section::make('Homepage Content Sections')
                                    ->description('Control visibility and text for homepage content blocks')
                                    ->schema([
                                        Toggle::make('section_how_it_works_enabled')->label('Enable "How It Works" Section')->default(true),
                                        TextInput::make('section_how_it_works_title')->label('How It Works Title')->default('How 2nd Nikah Works'),

                                        Toggle::make('section_why_us_enabled')->label('Enable "Why Choose Us" Section')->default(true),
                                        TextInput::make('section_why_us_title')->label('Why Choose Us Title')->default('Why Choose 2nd Nikah?'),

                                        Toggle::make('section_features_enabled')->label('Enable "Platform Features" Section')->default(true),
                                        TextInput::make('section_features_title')->label('Features Title')->default('Platform Features'),

                                        Toggle::make('section_cta_enabled')->label('Enable "Bottom CTA" Section')->default(true),
                                        TextInput::make('section_cta_title')->label('CTA Title')->default('Ready to Start Your Journey?'),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('App Download')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->schema([
                                Section::make('Mobile App Section Controls')
                                    ->schema([
                                        Toggle::make('app_download_section_enabled')
                                            ->label('Enable App Download Section')
                                            ->default(true),
                                        TextInput::make('app_download_title')
                                            ->label('Section Title')
                                            ->default('Take 2nd Nikah Wherever You Go'),
                                        Textarea::make('app_download_description')
                                            ->label('Section Description')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Google Play Store')
                                    ->schema([
                                        Toggle::make('app_download_google_play_enabled')->label('Enable Google Play Button')->default(true),
                                        TextInput::make('app_download_google_play_url')
                                            ->label('Google Play Store URL')
                                            ->placeholder('https://play.google.com/store/apps/details?id=...'),
                                        FileUpload::make('google_play_badge')
                                            ->label('Custom Google Play Badge')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/app')
                                            ->visibility('public')
                                            ->maxSize(10240),
                                     ])->columns(2),

                                Section::make('Apple App Store')
                                    ->schema([
                                        Toggle::make('app_download_apple_store_enabled')->label('Enable Apple App Store Button')->default(true),
                                        TextInput::make('app_download_apple_store_url')
                                            ->label('Apple App Store URL')
                                            ->placeholder('https://apps.apple.com/app/...'),
                                        FileUpload::make('app_store_badge')
                                            ->label('Custom App Store Badge')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/app')
                                            ->visibility('public')
                                            ->maxSize(10240),
                                    ])->columns(2),

                                Section::make('App Preview Mockup')
                                    ->schema([
                                        FileUpload::make('app_preview_image')
                                            ->label('Mobile App Preview / Mockup Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/app')
                                            ->visibility('public')
                                            ->maxSize(10240)
                                            ->columnSpanFull(),
                                     ]),
                            ]),

                        Tabs\Tab::make('Footer & Social')
                            ->icon('heroicon-o-rectangle-group')
                            ->schema([
                                Section::make('Footer Branding')
                                    ->schema([
                                        FileUpload::make('footer_logo_path')
                                            ->label('Footer Custom Logo')
                                            ->image()
                                            ->disk('public')
                                            ->directory('site/footer')
                                            ->visibility('public')
                                            ->maxSize(10240),
                                        Textarea::make('footer_description')
                                            ->label('Footer Summary Text')
                                            ->rows(2),
                                        TextInput::make('footer_copyright')
                                            ->label('Copyright Notice')
                                            ->default('All rights reserved.'),
                                    ])->columns(2),

                                Section::make('Social Links')
                                    ->description('Icons render on frontend only when valid URL is entered')
                                    ->schema([
                                        TextInput::make('social_facebook')->label('Facebook URL'),
                                        TextInput::make('social_instagram')->label('Instagram URL'),
                                        TextInput::make('social_youtube')->label('YouTube URL'),
                                        TextInput::make('social_linkedin')->label('LinkedIn URL'),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('Contact & Company')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Section::make('Company Information')
                                    ->schema([
                                        TextInput::make('company_name')->label('Company / Organization Name')->default('2nd Nikah Matrimonial'),
                                        TextInput::make('company_email')->label('Support Email')->default('support@2ndnikah.com'),
                                        TextInput::make('company_phone')->label('Contact Phone')->default('+880 1674 845391'),
                                        Textarea::make('company_address')->label('Physical Address')->rows(2)->columnSpanFull(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            if (is_array($value)) {
                $value = ! empty($value) ? array_values($value)[0] : null;
            }
            $type = is_bool($value) ? 'boolean' : 'string';
            Setting::set($key, $value, 'general', $type);
        }

        // Keep synonyms in sync
        if (isset($state['app_download_google_play_url'])) {
            Setting::set('play_store_url', is_array($state['app_download_google_play_url']) ? (array_values($state['app_download_google_play_url'])[0] ?? '') : $state['app_download_google_play_url']);
        }
        if (isset($state['app_download_apple_store_url'])) {
            Setting::set('app_store_url', is_array($state['app_download_apple_store_url']) ? (array_values($state['app_download_apple_store_url'])[0] ?? '') : $state['app_download_apple_store_url']);
        }

        Notification::make()
            ->title('Website Settings Saved Successfully')
            ->success()
            ->send();
    }
}
