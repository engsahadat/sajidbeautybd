<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'Beauty Wholesale BD',
                'company' => 'Beauty Wholesale Bangladesh Ltd.',
                'contact_name' => 'Ahmed Hassan',
                'email' => 'ahmed@beautywholesale.bd',
                'phone' => '+8801712345678',
                'address_line_1' => '123 Beauty Street',
                'address_line_2' => 'Gulshan-2',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postal_code' => '1212',
                'country' => 'BD',
                'website' => 'https://beautywholesale.bd',
                'status' => 'active',
                'notes' => 'Primary beauty products supplier with competitive prices.',
            ],
            [
                'name' => 'Cosmetics Direct',
                'company' => 'Cosmetics Direct Ltd.',
                'contact_name' => 'Fatima Rahman',
                'email' => 'fatima@cosmeticsdirect.com',
                'phone' => '+8801798765432',
                'address_line_1' => '456 Commercial Area',
                'address_line_2' => 'Dhanmondi',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postal_code' => '1205',
                'country' => 'BD',
                'website' => 'https://cosmeticsdirect.com',
                'status' => 'active',
                'notes' => 'Specializes in imported cosmetics and beauty tools.',
            ],
            [
                'name' => 'Rose Essence Suppliers',
                'company' => 'Rose Essence Suppliers Pvt. Ltd.',
                'contact_name' => 'Mohammad Ali',
                'email' => 'ali@roseessence.bd',
                'phone' => '+8801556789012',
                'address_line_1' => '789 Industrial Zone',
                'address_line_2' => 'Savar',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postal_code' => '1340',
                'country' => 'BD',
                'website' => null,
                'status' => 'active',
                'notes' => 'Local supplier of natural rose water and organic beauty products.',
            ],
            [
                'name' => 'Global Beauty Corp',
                'company' => 'Global Beauty Corporation',
                'contact_name' => 'Sarah Johnson',
                'email' => 'sarah@globalbeauty.com',
                'phone' => '+1234567890',
                'address_line_1' => '123 Beauty Plaza',
                'address_line_2' => 'Suite 100',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'US',
                'website' => 'https://globalbeauty.com',
                'status' => 'active',
                'notes' => 'International supplier for premium beauty brands.',
            ],
            [
                'name' => 'Local Beauty Hub',
                'company' => 'Local Beauty Hub',
                'contact_name' => 'Nasir Ahmed',
                'email' => 'nasir@localbeautyhub.bd',
                'phone' => '+8801634567890',
                'address_line_1' => '321 New Market',
                'address_line_2' => 'Shop 45',
                'city' => 'Chittagong',
                'state' => 'Chittagong Division',
                'postal_code' => '4000',
                'country' => 'BD',
                'website' => null,
                'status' => 'inactive',
                'notes' => 'Currently inactive due to supply chain issues.',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create([
                'name' => $vendor['name'],
                'company' => $vendor['company'],
                'contact_name' => $vendor['contact_name'],
                'email' => $vendor['email'],
                'phone' => $vendor['phone'],
                'address_line_1' => $vendor['address_line_1'],
                'address_line_2' => $vendor['address_line_2'],
                'city' => $vendor['city'],
                'state' => $vendor['state'],
                'postal_code' => $vendor['postal_code'],
                'country' => $vendor['country'],
                'website' => $vendor['website'],
                'status' => $vendor['status'],
                'notes' => $vendor['notes'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}