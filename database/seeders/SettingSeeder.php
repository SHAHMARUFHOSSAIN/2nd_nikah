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
            // General & Branding
            ['group' => 'general', 'key' => 'site_name', 'value' => '2nd Nikah', 'type' => 'string', 'description' => 'Platform name.'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Every Heart Deserves a 2nd Chance', 'type' => 'string', 'description' => 'Platform tagline.'],
            ['group' => 'branding', 'key' => 'logo_path', 'value' => '', 'type' => 'string', 'description' => 'Logo image path.'],
            ['group' => 'branding', 'key' => 'favicon_path', 'value' => '', 'type' => 'string', 'description' => 'Favicon image path.'],
            ['group' => 'branding', 'key' => 'primary_color', 'value' => '#E11D48', 'type' => 'string', 'description' => 'Primary brand color.'],
            ['group' => 'branding', 'key' => 'secondary_color', 'value' => '#F472B6', 'type' => 'string', 'description' => 'Secondary brand color.'],

            // Hero Section CMS
            ['group' => 'hero', 'key' => 'hero_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable Hero section.'],
            ['group' => 'hero', 'key' => 'hero_heading', 'value' => 'Find Your Second Chance at Happiness', 'type' => 'string', 'description' => 'Hero heading.'],
            ['group' => 'hero', 'key' => 'hero_subtitle', 'value' => 'A dignified, mature matrimonial platform built with privacy, respect, and sincerity.', 'type' => 'string', 'description' => 'Hero subtitle.'],
            ['group' => 'hero', 'key' => 'hero_description', 'value' => 'Connect with verified members seeking meaningful second marriages in a safe and supportive environment.', 'type' => 'string', 'description' => 'Hero description.'],
            ['group' => 'hero', 'key' => 'hero_cta_primary_text', 'value' => 'Create Free Account', 'type' => 'string', 'description' => 'Hero primary CTA text.'],
            ['group' => 'hero', 'key' => 'hero_cta_primary_url', 'value' => '/register', 'type' => 'string', 'description' => 'Hero primary CTA URL.'],
            ['group' => 'hero', 'key' => 'hero_cta_secondary_text', 'value' => 'Browse Directory', 'type' => 'string', 'description' => 'Hero secondary CTA text.'],
            ['group' => 'hero', 'key' => 'hero_cta_secondary_url', 'value' => '/members', 'type' => 'string', 'description' => 'Hero secondary CTA URL.'],
            ['group' => 'hero', 'key' => 'hero_image', 'value' => '', 'type' => 'string', 'description' => 'Hero background/banner image.'],

            // Homepage Sections Controls
            ['group' => 'homepage', 'key' => 'section_how_it_works_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable How It Works section.'],
            ['group' => 'homepage', 'key' => 'section_how_it_works_title', 'value' => 'How 2nd Nikah Works', 'type' => 'string', 'description' => 'How It Works section title.'],
            ['group' => 'homepage', 'key' => 'section_how_it_works_subtitle', 'value' => 'Three simple steps to finding your lifelong partner with dignity.', 'type' => 'string', 'description' => 'How It Works section subtitle.'],
            ['group' => 'homepage', 'key' => 'section_why_us_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable Why 2nd Nikah section.'],
            ['group' => 'homepage', 'key' => 'section_why_us_title', 'value' => 'Why Choose 2nd Nikah?', 'type' => 'string', 'description' => 'Why Us section title.'],
            ['group' => 'homepage', 'key' => 'section_why_us_subtitle', 'value' => 'Designed specifically for widows, divorcees, and mature singles seeking a serious match.', 'type' => 'string', 'description' => 'Why Us section subtitle.'],
            ['group' => 'homepage', 'key' => 'section_features_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable Features section.'],
            ['group' => 'homepage', 'key' => 'section_features_title', 'value' => 'Platform Features', 'type' => 'string', 'description' => 'Features section title.'],
            ['group' => 'homepage', 'key' => 'section_features_subtitle', 'value' => 'Built around privacy, security, and verified profiles.', 'type' => 'string', 'description' => 'Features section subtitle.'],
            ['group' => 'homepage', 'key' => 'section_cta_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable CTA section.'],
            ['group' => 'homepage', 'key' => 'section_cta_title', 'value' => 'Ready to Start Your Journey?', 'type' => 'string', 'description' => 'CTA section title.'],
            ['group' => 'homepage', 'key' => 'section_cta_subtitle', 'value' => 'Register today and browse verified profiles in a respectful environment.', 'type' => 'string', 'description' => 'CTA section subtitle.'],

            // App Downloads (Part H)
            ['group' => 'apps', 'key' => 'app_download_section_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable App Download section.'],
            ['group' => 'apps', 'key' => 'app_download_title', 'value' => 'Download 2nd Nikah Mobile App', 'type' => 'string', 'description' => 'App download section title.'],
            ['group' => 'apps', 'key' => 'app_download_description', 'value' => 'Stay connected on the go with our official mobile app available for Android and iOS.', 'type' => 'string', 'description' => 'App download section description.'],
            ['group' => 'apps', 'key' => 'app_download_google_play_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable Google Play download button.'],
            ['group' => 'apps', 'key' => 'app_download_google_play_url', 'value' => 'https://play.google.com/store/apps/details?id=com.ndnikah.app', 'type' => 'string', 'description' => 'Google Play Store App URL.'],
            ['group' => 'apps', 'key' => 'app_download_google_play_label', 'value' => 'Get it on Google Play', 'type' => 'string', 'description' => 'Google Play Store button label.'],
            ['group' => 'apps', 'key' => 'app_download_apple_store_enabled', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable Apple App Store download button.'],
            ['group' => 'apps', 'key' => 'app_download_apple_store_url', 'value' => 'https://apps.apple.com/app/2nd-nikah/id123456789', 'type' => 'string', 'description' => 'Apple App Store URL.'],
            ['group' => 'apps', 'key' => 'app_download_apple_store_label', 'value' => 'Download on the App Store', 'type' => 'string', 'description' => 'Apple App Store button label.'],

            // Footer CMS & Contact Info (Part I & K)
            ['group' => 'footer', 'key' => 'footer_description', 'value' => 'A dignified, trustworthy matrimonial platform designed with privacy, integrity, and respect.', 'type' => 'string', 'description' => 'Footer summary text.'],
            ['group' => 'footer', 'key' => 'footer_copyright', 'value' => 'All rights reserved.', 'type' => 'string', 'description' => 'Footer copyright text.'],
            ['group' => 'contact', 'key' => 'company_name', 'value' => '2nd Nikah Matrimonial', 'type' => 'string', 'description' => 'Company name.'],
            ['group' => 'contact', 'key' => 'company_email', 'value' => 'support@2ndnikah.com', 'type' => 'string', 'description' => 'Contact email.'],
            ['group' => 'contact', 'key' => 'company_phone', 'value' => '+880 1674 845391', 'type' => 'string', 'description' => 'Contact phone number.'],
            ['group' => 'contact', 'key' => 'company_address', 'value' => '27/2 Joginagar Len Wari, Dhaka 1203, Bangladesh', 'type' => 'string', 'description' => 'Company address.'],
            ['group' => 'social', 'key' => 'social_facebook', 'value' => 'https://facebook.com', 'type' => 'string', 'description' => 'Facebook page URL.'],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => 'https://instagram.com', 'type' => 'string', 'description' => 'Instagram page URL.'],
            ['group' => 'social', 'key' => 'social_youtube', 'value' => 'https://youtube.com', 'type' => 'string', 'description' => 'YouTube channel URL.'],
            ['group' => 'social', 'key' => 'social_linkedin', 'value' => 'https://linkedin.com', 'type' => 'string', 'description' => 'LinkedIn page URL.'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
