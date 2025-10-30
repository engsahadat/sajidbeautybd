<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first category and brand for seeding
        $category = Category::first();
        $brand = Brand::first();

        if (!$category || !$brand) {
            return; // Skip if no categories or brands exist
        }

        $products = [
            [
                'name' => 'Rose Water Premium',
                'slug' => 'rose-water-premium',
                'description' => 'Premium quality rose water for natural skincare. Made from fresh rose petals.',
                'short_description' => 'Premium quality rose water for natural skincare.',
                'sku' => 'RW-001',
                'price' => 150.00,
                'sale_price' => 120.00,
                'stock_quantity' => 50,
                'weight' => 0.25,
                'dimensions' => '15x5x5',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Rose Water Premium - Natural Skincare',
                'meta_description' => 'Premium quality rose water for natural skincare routine.',
            ],
            [
                'name' => 'Foundation Liquid Base',
                'slug' => 'foundation-liquid-base',
                'description' => 'Long-lasting liquid foundation with full coverage. Suitable for all skin types.',
                'short_description' => 'Long-lasting liquid foundation with full coverage.',
                'sku' => 'FN-001',
                'price' => 250.00,
                'sale_price' => null,
                'stock_quantity' => 30,
                'weight' => 0.15,
                'dimensions' => '10x3x3',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'meta_title' => 'Foundation Liquid Base - Full Coverage',
                'meta_description' => 'Long-lasting liquid foundation perfect for daily use.',
            ],
            [
                'name' => 'Waterproof Kajol',
                'slug' => 'waterproof-kajol',
                'description' => 'Smudge-proof and waterproof kajol for intense eye makeup. Long-lasting formula.',
                'short_description' => 'Smudge-proof and waterproof kajol for intense eyes.',
                'sku' => 'KJ-001',
                'price' => 80.00,
                'sale_price' => 65.00,
                'stock_quantity' => 100,
                'weight' => 0.05,
                'dimensions' => '12x1x1',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Waterproof Kajol - Intense Eye Makeup',
                'meta_description' => 'Smudge-proof waterproof kajol for beautiful eyes.',
            ],
            [
                'name' => 'Premium Razor Blades Pack',
                'slug' => 'premium-razor-blades-pack',
                'description' => 'Sharp and durable razor blades for smooth shaving experience. Pack of 10 blades.',
                'short_description' => 'Sharp and durable razor blades pack of 10.',
                'sku' => 'RB-001',
                'price' => 120.00,
                'sale_price' => null,
                'stock_quantity' => 75,
                'weight' => 0.10,
                'dimensions' => '8x5x2',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'Premium Razor Blades Pack - Smooth Shaving',
                'meta_description' => 'High-quality razor blades for professional shaving.',
            ],
            [
                'name' => 'Black Eye Liner Pencil',
                'slug' => 'black-eye-liner-pencil',
                'description' => 'Intense black eye liner pencil for precise application. Smooth and easy to apply.',
                'short_description' => 'Intense black eye liner pencil for precise lines.',
                'sku' => 'EL-001',
                'price' => 60.00,
                'sale_price' => 45.00,
                'stock_quantity' => 120,
                'weight' => 0.03,
                'dimensions' => '15x1x1',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 5,
                'meta_title' => 'Black Eye Liner Pencil - Precise Application',
                'meta_description' => 'Professional black eye liner for beautiful eye makeup.',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'slug' => $product['slug'],
                'description' => $product['description'],
                'short_description' => $product['short_description'],
                'sku' => $product['sku'],
                'price' => $product['price'],
                'sale_price' => $product['sale_price'],
                'stock_quantity' => $product['stock_quantity'],
                'manage_stock' => true,
                'stock_status' => 'in_stock',
                'weight' => $product['weight'],
                'dimensions' => $product['dimensions'],
                'is_active' => $product['is_active'],
                'is_featured' => $product['is_featured'],
                'sort_order' => $product['sort_order'],
                'meta_title' => $product['meta_title'],
                'meta_description' => $product['meta_description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}