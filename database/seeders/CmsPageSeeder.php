<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>About 2nd Nikah</h2><p>2nd Nikah is a dignified, secure matrimonial service specifically created to assist widows, divorcees, and mature individuals in finding a compatible life partner for a second marriage in full compliance with Islamic principles and mutual respect.</p>',
                'is_published' => true,
                'meta_title' => 'About 2nd Nikah - Every Heart Deserves a 2nd Chance',
                'meta_description' => 'Learn more about 2nd Nikah, the premier matrimonial service for second marriages.',
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<h2>Terms of Service</h2><p>By registering on 2nd Nikah, you agree to provide truthful information, treat all members with respect and dignity, and use the platform solely for legitimate matrimonial purposes.</p>',
                'is_published' => true,
                'meta_title' => 'Terms & Conditions - 2nd Nikah',
                'meta_description' => 'Terms and conditions governing the use of the 2nd Nikah matrimonial platform.',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2><p>We prioritize your privacy. Your contact details, phone number, and private communication are protected and never shared publicly or exposed without your explicit consent.</p>',
                'is_published' => true,
                'meta_title' => 'Privacy Policy - 2nd Nikah',
                'meta_description' => 'Detailed privacy policy of 2nd Nikah platform.',
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'content' => '<h2>Refund Policy</h2><p>All membership subscription payments made via SSLCommerz are processed securely. Subscriptions provide immediate digital access to premium features.</p>',
                'is_published' => true,
                'meta_title' => 'Refund Policy - 2nd Nikah',
                'meta_description' => 'Refund and payment policy for 2nd Nikah subscriptions.',
            ],
        ];

        foreach ($pages as $page) {
            CmsPage::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
