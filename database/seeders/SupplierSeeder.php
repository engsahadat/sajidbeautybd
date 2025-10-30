<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $vendors = Vendor::all();

        if ($products->count() === 0 || $vendors->count() === 0) {
            return; // Skip if no products or vendors exist
        }

        $suppliers = [];

        foreach ($products as $index => $product) {
            // Assign primary supplier (first vendor)
            if ($vendors->count() > 0) {
                $suppliers[] = [
                    'product_id' => $product->id,
                    'vendor_id' => $vendors[0]->id,
                    'supplier_sku' => 'SUP-' . $product->sku . '-001',
                    'cost_price' => $product->price * 0.6, // 60% of selling price
                    'minimum_order_quantity' => 10,
                    'lead_time_days' => 7,
                    'is_primary' => true,
                ];
            }

            // Assign secondary supplier for some products
            if ($vendors->count() > 1 && $index % 2 === 0) {
                $suppliers[] = [
                    'product_id' => $product->id,
                    'vendor_id' => $vendors[1]->id,
                    'supplier_sku' => 'SUP-' . $product->sku . '-002',
                    'cost_price' => $product->price * 0.65, // 65% of selling price
                    'minimum_order_quantity' => 20,
                    'lead_time_days' => 10,
                    'is_primary' => false,
                ];
            }

            // Assign third supplier for featured products
            if ($vendors->count() > 2 && $product->is_featured) {
                $suppliers[] = [
                    'product_id' => $product->id,
                    'vendor_id' => $vendors[2]->id,
                    'supplier_sku' => 'SUP-' . $product->sku . '-003',
                    'cost_price' => $product->price * 0.55, // 55% of selling price
                    'minimum_order_quantity' => 5,
                    'lead_time_days' => 14,
                    'is_primary' => false,
                ];
            }
        }

        foreach ($suppliers as $supplier) {
            Supplier::create([
                'product_id' => $supplier['product_id'],
                'vendor_id' => $supplier['vendor_id'],
                'supplier_sku' => $supplier['supplier_sku'],
                'cost_price' => $supplier['cost_price'],
                'minimum_order_quantity' => $supplier['minimum_order_quantity'],
                'lead_time_days' => $supplier['lead_time_days'],
                'is_primary' => $supplier['is_primary'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}