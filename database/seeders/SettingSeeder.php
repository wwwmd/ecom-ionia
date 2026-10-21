<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['general', 'site_name',        'store Store',                          'string'],
            ['general', 'site_tagline',     'Think Beyond... Do Beyond',             'string'],
            ['general', 'site_email',       'hello@store.com',                      'string'],
            ['general', 'site_phone',       '+91 99999 99999',                       'string'],
            ['general', 'site_address',     '123 Business Park, Mumbai, India',      'text'],
            ['general', 'currency',         'INR',                                    'string'],
            ['general', 'currency_symbol',  '₹',                                      'string'],
            ['general', 'timezone',         'Asia/Kolkata',                           'string'],

            // E-commerce
            ['ecommerce', 'tax_rate',           '18',   'float'],
            ['ecommerce', 'shipping_flat_rate', '49',   'float'],
            ['ecommerce', 'free_shipping_min',  '999',  'float'],
            ['ecommerce', 'low_stock_threshold','5',    'int'],

            // SEO
            ['seo', 'meta_title',       'store Store — Premium Shopping Experience', 'string'],
            ['seo', 'meta_description', 'Shop premium products at store. Fast shipping, secure payments.', 'text'],
        ];

        foreach ($settings as [$group, $key, $value, $type]) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['group' => $group, 'value' => $value, 'type' => $type]
            );
        }

        $this->command->info('✅ Settings seeded.');
    }
}
