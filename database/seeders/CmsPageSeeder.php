<?php

namespace Database\Seeders;

use App\Models\Cms_page;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::where('email', 'admin@sajidbeauty.com')->first() ?? User::first();

        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About Sajid Beauty BD</h1>
                <p>Welcome to Sajid Beauty BD, your premier destination for authentic beauty products in Bangladesh. We have been serving our customers with high-quality cosmetics, skincare, and beauty accessories for years.</p>
                <h2>Our Mission</h2>
                <p>To provide affordable, authentic, and high-quality beauty products to enhance your natural beauty and boost your confidence.</p>
                <h2>Why Choose Us?</h2>
                <ul>
                <li>100% Authentic Products</li>
                <li>Competitive Prices</li>
                <li>Fast Delivery</li>
                <li>Expert Customer Support</li>
                <li>Wide Range of Products</li>
                </ul>',
                'meta_title' => 'About Us - Sajid Beauty BD',
                'meta_description' => 'Learn about Sajid Beauty BD, your trusted beauty partner in Bangladesh offering authentic cosmetics and skincare products.',
                'meta_keywords' => 'about us, sajid beauty, bangladesh, beauty products, cosmetics',
                'status' => 'active',
                'featured_image' => 'images/cms/about-us.jpg',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1>
                <p>Last updated: ' . date('F d, Y') . '</p>
                <h2>Information We Collect</h2>
                <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>
                <h2>Information Sharing</h2>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
                <h2>Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at privacy@sajidbeauty.com</p>',
                'meta_title' => 'Privacy Policy - Sajid Beauty BD',
                'meta_description' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
                'meta_keywords' => 'privacy policy, data protection, personal information',
                'status' => 'active',
                'featured_image' => null,
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'content' => '<h1>Terms & Conditions</h1>
                <p>Last updated: ' . date('F d, Y') . '</p>
                <h2>Acceptance of Terms</h2>
                <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
                <h2>Product Information</h2>
                <p>We strive to provide accurate product information, but we do not warrant that product descriptions or other content is accurate, complete, reliable, current, or error-free.</p>
                <h2>Orders and Payment</h2>
                <p>All orders are subject to acceptance and availability. Payment must be received before products are shipped.</p>
                <h2>Shipping and Delivery</h2>
                <p>We aim to process and ship orders within 1-2 business days. Delivery times may vary based on location and shipping method selected.</p>
                <h2>Returns and Refunds</h2>
                <p>We accept returns within 7 days of delivery for unopened products in original packaging. Refunds will be processed within 5-7 business days.</p>
                <h2>Contact Information</h2>
                <p>For questions about these Terms & Conditions, please contact us at support@sajidbeauty.com</p>',
                'meta_title' => 'Terms & Conditions - Sajid Beauty BD',
                'meta_description' => 'Read our terms and conditions for purchasing and using our beauty products and services.',
                'meta_keywords' => 'terms conditions, purchase terms, return policy',
                'status' => 'active',
                'featured_image' => null,
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'Shipping Information',
                'slug' => 'shipping-information',
                'content' => '<h1>Shipping Information</h1>
                <h2>Shipping Methods</h2>
                <p>We offer several shipping options to meet your needs:</p>
                <ul>
                <li><strong>Standard Delivery (3-5 days):</strong> 60 BDT</li>
                <li><strong>Express Delivery (1-2 days):</strong> 120 BDT</li>
                <li><strong>Same Day Delivery (Dhaka only):</strong> 200 BDT</li>
                </ul>
                <h2>Free Shipping</h2>
                <p>Enjoy free standard shipping on orders over 500 BDT within Bangladesh.</p>
                <h2>Processing Time</h2>
                <p>Orders are typically processed within 1-2 business days. You will receive a confirmation email with tracking information once your order ships.</p>
                <h2>Delivery Areas</h2>
                <p>We deliver throughout Bangladesh. Delivery times may vary for remote areas.</p>
                <h2>Track Your Order</h2>
                <p>You can track your order status in your account or use the tracking number provided in your shipping confirmation email.</p>',
                'meta_title' => 'Shipping Information - Sajid Beauty BD',
                'meta_description' => 'Learn about our shipping methods, delivery times, and shipping costs for beauty products.',
                'meta_keywords' => 'shipping, delivery, bangladesh, shipping cost',
                'status' => 'active',
                'featured_image' => 'images/cms/shipping.jpg',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'Beauty Tips & Guides',
                'slug' => 'beauty-tips-guides',
                'content' => '<h1>Beauty Tips & Guides</h1>
                <h2>Skincare Routine</h2>
                <p>Follow these essential steps for healthy, glowing skin:</p>
                <ol>
                <li><strong>Cleanse:</strong> Use a gentle cleanser twice daily</li>
                <li><strong>Tone:</strong> Apply toner to balance pH</li>
                <li><strong>Moisturize:</strong> Keep skin hydrated</li>
                <li><strong>Protect:</strong> Use sunscreen daily</li>
                </ol>
                <h2>Makeup Application Tips</h2>
                <ul>
                <li>Always start with clean, moisturized skin</li>
                <li>Use primer for longer-lasting makeup</li>
                <li>Apply foundation in thin layers</li>
                <li>Blend well for natural-looking results</li>
                </ul>
                <h2>Product Care</h2>
                <p>Keep your beauty products in optimal condition:</p>
                <ul>
                <li>Store in cool, dry places</li>
                <li>Check expiration dates regularly</li>
                <li>Keep makeup brushes clean</li>
                <li>Close containers tightly after use</li>
                </ul>',
                'meta_title' => 'Beauty Tips & Guides - Sajid Beauty BD',
                'meta_description' => 'Discover expert beauty tips, skincare routines, and makeup application guides.',
                'meta_keywords' => 'beauty tips, skincare routine, makeup tips, beauty guide',
                'status' => 'draft',
                'featured_image' => 'images/cms/beauty-tips.jpg',
                'published_at' => null,
            ],
        ];

        foreach ($pages as $page) {
            Cms_page::create([
                'title' => $page['title'],
                'slug' => $page['slug'],
                'content' => $page['content'],
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'meta_keywords' => $page['meta_keywords'],
                'status' => $page['status'],
                'featured_image' => $page['featured_image'],
                'author_id' => $author?->id,
                'published_at' => $page['published_at'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}