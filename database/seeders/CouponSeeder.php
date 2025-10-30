<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% discount for new customers',
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_amount' => 100.00,
                'maximum_discount' => 50.00,
                'usage_limit' => 1000,
                'usage_limit_per_customer' => 1,
                'used_count' => 0,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(3),
                'status' => 'active',
            ],
            [
                'code' => 'SAVE20',
                'name' => 'Save 20 BDT',
                'description' => 'Fixed 20 BDT discount on orders above 200 BDT',
                'type' => 'fixed',
                'value' => 20.00,
                'minimum_amount' => 200.00,
                'maximum_discount' => null,
                'usage_limit' => 500,
                'usage_limit_per_customer' => 2,
                'used_count' => 0,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(2),
                'status' => 'active',
            ],
            [
                'code' => 'BEAUTY15',
                'name' => 'Beauty Special',
                'description' => '15% discount on beauty products',
                'type' => 'percentage',
                'value' => 15.00,
                'minimum_amount' => 150.00,
                'maximum_discount' => 100.00,
                'usage_limit' => 200,
                'usage_limit_per_customer' => 3,
                'used_count' => 0,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonth(),
                'status' => 'active',
            ],
            [
                'code' => 'EXPIRED5',
                'name' => 'Expired Coupon',
                'description' => 'This coupon has expired',
                'type' => 'percentage',
                'value' => 5.00,
                'minimum_amount' => 50.00,
                'maximum_discount' => 25.00,
                'usage_limit' => 100,
                'usage_limit_per_customer' => 1,
                'used_count' => 50,
                'starts_at' => Carbon::now()->subMonths(2),
                'expires_at' => Carbon::now()->subMonth(),
                'status' => 'inactive',
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create([
                'code' => $coupon['code'],
                'name' => $coupon['name'],
                'description' => $coupon['description'],
                'type' => $coupon['type'],
                'value' => $coupon['value'],
                'minimum_amount' => $coupon['minimum_amount'],
                'maximum_discount' => $coupon['maximum_discount'],
                'usage_limit' => $coupon['usage_limit'],
                'usage_limit_per_customer' => $coupon['usage_limit_per_customer'],
                'used_count' => $coupon['used_count'],
                'starts_at' => $coupon['starts_at'],
                'expires_at' => $coupon['expires_at'],
                'status' => $coupon['status'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}