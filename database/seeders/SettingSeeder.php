<?php

namespace Database\Seeders;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key_name' => 'Sajid Beauty BD', 'value' => 'sajidbeautybd', 'type' => 'string', 'is_public' => true],
            ['key_name' => 'site_description', 'value' => 'Your online shopping destination', 'type' => 'string', 'is_public' => true],
            ['key_name' => 'default_currency', 'value' => 'USD', 'type' => 'string', 'is_public' => true],
            ['key_name' => 'enable_reviews', 'value' => '1', 'type' => 'boolean', 'is_public' => true],
            ['key_name' => 'enable_wishlist', 'value' => '1', 'type' => 'boolean', 'is_public' => true],
        ];
        foreach ($settings as $setting) {
            Setting::create([
                'key_name' => $setting['key_name'],
                'value' => $setting['value'],
                'type' => $setting['type'],
                'is_public' => $setting['is_public'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
