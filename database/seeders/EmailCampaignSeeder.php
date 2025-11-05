<?php

namespace Database\Seeders;

use App\Models\EmailCampaigns;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = User::where('email', 'admin@sajidbeauty.com')->first() ?? User::first();

        if (!$creator) {
            return; // Skip if no users exist
        }

        $campaigns = [
            [
                'name' => 'Welcome Newsletter',
                'subject' => 'Welcome to Sajid Beauty BD!',
                'content' => '<h1>Welcome to Sajid Beauty BD!</h1>
                <p>Thank you for joining our beauty community. Get ready to discover amazing beauty products at unbeatable prices.</p>
                <p>As a welcome gift, use code <strong>WELCOME10</strong> for 10% off your first order!</p>
                <p>Happy shopping!</p>',
                'type' => 'newsletter',
                'status' => 'sent',
                'scheduled_at' => Carbon::now()->subDays(7),
                'sent_at' => Carbon::now()->subDays(7),
                'recipient_count' => 150,
                'open_count' => 95,
                'click_count' => 25,
            ],
            [
                'name' => 'New Product Launch',
                'subject' => '🎉 New Beauty Products Just Arrived!',
                'content' => '<h1>New Arrivals Alert!</h1>
                <p>We are excited to announce the arrival of new premium beauty products!</p>
                <ul>
                <li>Premium Rose Water Collection</li>
                <li>Long-lasting Foundation Series</li>
                <li>Waterproof Makeup Range</li>
                </ul>
                <p>Shop now and get 15% off with code <strong>BEAUTY15</strong></p>',
                'type' => 'promotional',
                'status' => 'sent',
                'scheduled_at' => Carbon::now()->subDays(3),
                'sent_at' => Carbon::now()->subDays(3),
                'recipient_count' => 200,
                'open_count' => 120,
                'click_count' => 40,
            ],
            [
                'name' => 'Abandoned Cart Reminder',
                'subject' => 'You left something beautiful behind...',
                'content' => '<h1>Complete Your Purchase</h1>
                <p>You have items waiting in your cart. Don\'t miss out on these amazing beauty products!</p>
                <p>Complete your purchase now and get free shipping on orders over 500 BDT.</p>
                <p><a href="#" style="background: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none;">Complete Purchase</a></p>',
                'type' => 'abandoned_cart',
                'status' => 'scheduled',
                'scheduled_at' => Carbon::now()->addHours(2),
                'sent_at' => null,
                'recipient_count' => 0,
                'open_count' => 0,
                'click_count' => 0,
            ],
            [
                'name' => 'Monthly Beauty Tips',
                'subject' => 'Your Monthly Beauty Guide 💄',
                'content' => '<h1>Beauty Tips for This Month</h1>
                <p>Discover expert beauty tips and tricks to enhance your natural beauty:</p>
                <h2>Skincare Spotlight</h2>
                <p>This month, focus on hydration. Use our premium rose water daily for glowing skin.</p>
                <h2>Makeup Trend</h2>
                <p>Bold eye makeup is trending! Try our waterproof kajol for dramatic looks.</p>
                <p>Shop our featured products and save big!</p>',
                'type' => 'newsletter',
                'status' => 'draft',
                'scheduled_at' => null,
                'sent_at' => null,
                'recipient_count' => 0,
                'open_count' => 0,
                'click_count' => 0,
            ],
        ];

        foreach ($campaigns as $campaign) {
            EmailCampaigns::create([
                'name' => $campaign['name'],
                'subject' => $campaign['subject'],
                'content' => $campaign['content'],
                'type' => $campaign['type'],
                'status' => $campaign['status'],
                'scheduled_at' => $campaign['scheduled_at'],
                'sent_at' => $campaign['sent_at'],
                'recipient_count' => $campaign['recipient_count'],
                'open_count' => $campaign['open_count'],
                'click_count' => $campaign['click_count'],
                'created_by' => $creator->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}