<?php

namespace Database\Seeders;

use App\Models\FrontendSetting;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrontendSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Frontend Settings
        $settings = [
            'site_title' => 'Review Pro',
            'site_tagline' => 'Amazon Review Management Bot',
            'hero_title' => 'Automate Your Amazon Review Management',
            'hero_subtitle' => 'Streamline your review campaigns, manage multiple accounts, and boost your Amazon presence with our powerful automation bot.',
            'cta_text' => 'Get Started Today',
            'contact_email' => 'support@reviewpro.com',
            'contact_phone' => '+1 (555) 123-4567',
            'whatsapp_number' => '15551234567',
            'telegram_username' => '@reviewpro',
            'facebook_url' => 'https://facebook.com/reviewpro',
        ];

        foreach ($settings as $key => $value) {
            FrontendSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Sample Packages
        Package::updateOrCreate(
            ['name' => 'Starter'],
            [
                'price' => 49.00,
                'duration' => 'month',
                'features' => [
                    'Up to 10 Amazon accounts',
                    '100 reviews per month',
                    'Basic analytics dashboard',
                    'Email support',
                    '5 active projects',
                ],
                'is_popular' => false,
                'is_active' => true,
                'order' => 1
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Professional'],
            [
                'price' => 99.00,
                'duration' => 'month',
                'features' => [
                    'Up to 50 Amazon accounts',
                    '500 reviews per month',
                    'Advanced analytics & insights',
                    'Priority support',
                    'Unlimited projects',
                    'API access',
                ],
                'is_popular' => true,
                'is_active' => true,
                'order' => 2
            ]
        );

        Package::updateOrCreate(
            ['name' => 'Enterprise'],
            [
                'price' => 299.00,
                'duration' => 'month',
                'features' => [
                    'Unlimited Amazon accounts',
                    'Unlimited reviews',
                    'Custom analytics dashboards',
                    '24/7 phone support',
                    'Unlimited projects',
                    'Dedicated account manager',
                    'White-label options',
                ],
                'is_popular' => false,
                'is_active' => true,
                'order' => 3
            ]
        );
    }
}
