<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() === 0) {
            return; // Skip if no users exist
        }

        $notifications = [
            [
                'type' => 'order_confirmation',
                'title' => 'Order Confirmed',
                'message' => 'Your order #1001 has been confirmed and is being processed.',
                'data' => json_encode(['order_id' => 1001, 'amount' => 250.00]),
                'read_at' => null,
            ],
            [
                'type' => 'shipping_update',
                'title' => 'Order Shipped',
                'message' => 'Your order #1001 has been shipped and is on its way to you.',
                'data' => json_encode(['order_id' => 1001, 'tracking_number' => 'TRK123456789']),
                'read_at' => Carbon::now()->subHours(2),
            ],
            [
                'type' => 'promotional',
                'title' => 'Special Offer!',
                'message' => 'Get 20% off on all beauty products. Limited time offer!',
                'data' => json_encode(['coupon_code' => 'BEAUTY20', 'expires_at' => Carbon::now()->addDays(7)]),
                'read_at' => null,
            ],
            [
                'type' => 'low_stock',
                'title' => 'Product Back in Stock',
                'message' => 'Rose Water Premium is back in stock! Get yours before it runs out again.',
                'data' => json_encode(['product_id' => 1, 'product_name' => 'Rose Water Premium']),
                'read_at' => Carbon::now()->subDays(1),
            ],
            [
                'type' => 'account_update',
                'title' => 'Profile Updated',
                'message' => 'Your profile information has been successfully updated.',
                'data' => json_encode(['updated_fields' => ['phone', 'address']]),
                'read_at' => Carbon::now()->subDays(3),
            ],
            [
                'type' => 'wishlist',
                'title' => 'Wishlist Item on Sale',
                'message' => 'Good news! Waterproof Kajol from your wishlist is now on sale.',
                'data' => json_encode(['product_id' => 3, 'sale_price' => 65.00, 'original_price' => 80.00]),
                'read_at' => null,
            ],
        ];

        foreach ($users as $user) {
            // Create 2-3 notifications per user
            $userNotifications = array_slice($notifications, 0, rand(2, 4));
            
            foreach ($userNotifications as $notification) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notification['type'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'data' => $notification['data'],
                    'read_at' => $notification['read_at'],
                    'created_at' => Carbon::now()->subDays(rand(0, 7)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}