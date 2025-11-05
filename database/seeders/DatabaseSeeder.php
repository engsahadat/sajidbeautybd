<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            SettingSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            VendorSeeder::class,
            SupplierSeeder::class,
            CmsPageSeeder::class,
            EmailCampaignSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
