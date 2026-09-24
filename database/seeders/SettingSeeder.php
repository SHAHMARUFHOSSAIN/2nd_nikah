<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Only system configuration settings - ABSOLUTELY NO FAKE USER OR MATRIMONIAL DATA.
     */
    public function run(): void
    {
        $defaultSettings = [
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => '2nd Nikah',
                'type' => 'string',
                'description' => 'The public site name for the platform.',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Every Heart Deserves a 2nd Chance',
                'type' => 'string',
                'description' => 'The official tagline of 2nd Nikah.',
            ],
            [
                'group' => 'branding',
                'key' => 'primary_color',
                'value' => '#E11D48',
                'type' => 'string',
                'description' => 'Primary brand color hex code.',
            ],
            [
                'group' => 'branding',
                'key' => 'secondary_color',
                'value' => '#F472B6',
                'type' => 'string',
                'description' => 'Secondary brand color hex code.',
            ],
            [
                'group' => 'branding',
                'key' => 'accent_color',
                'value' => '#FB7185',
                'type' => 'string',
                'description' => 'Accent color hex code.',
            ],
            [
                'group' => 'localization',
                'key' => 'currency',
                'value' => 'USD',
                'type' => 'string',
                'description' => 'Default platform currency code.',
            ],
            [
                'group' => 'localization',
                'key' => 'currency_symbol',
                'value' => '$',
                'type' => 'string',
                'description' => 'Default platform currency symbol.',
            ],
            [
                'group' => 'registration',
                'key' => 'registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable or disable new user registration.',
            ],
            [
                'group' => 'verification',
                'key' => 'email_verification_required',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Require email verification before user account activation.',
            ],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
