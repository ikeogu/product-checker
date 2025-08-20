<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Paracetamol 500mg Tablet',
                'category' => 'Analgesic',
                'barcode' => '8901234567890',
                'nafdac_number' => '04-1234',
                'batch_number' => 'PCM2025A',
                'expiry_date' => Carbon::now()->addYears(2),
                'manufacture_date' => Carbon::now()->subMonths(6),
                'manufacturer' => 'Emzor Pharmaceutical Industries Ltd',
                'country_of_origin' => 'Nigeria',
                'image_url' => 'https://example.com/images/paracetamol.jpg',
                'status' => 'available',
                'description' => 'Used to relieve pain and reduce fever.',
                'active_ingredient' => 'Paracetamol',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'packaging' => 'Blister pack of 10 tablets',
                'prescription_required' => false,
                'volume_or_weight' => '10 tablets',
                'nutritional_info' => null,
                'flavour' => null,
                'storage_instructions' => 'Store in a cool dry place below 30°C.',
                'added_by' => 1,
            ],
            [
                'name' => 'Amoxicillin 500mg Capsule',
                'category' => 'Antibiotic',
                'barcode' => '8901234567891',
                'nafdac_number' => '04-5678',
                'batch_number' => 'AMX2025B',
                'expiry_date' => Carbon::now()->addYears(1),
                'manufacture_date' => Carbon::now()->subMonths(3),
                'manufacturer' => 'GlaxoSmithKline',
                'country_of_origin' => 'UK',
                'image_url' => 'https://example.com/images/amoxicillin.jpg',
                'status' => 'available',
                'description' => 'Broad-spectrum antibiotic used to treat bacterial infections.',
                'active_ingredient' => 'Amoxicillin',
                'dosage_form' => 'Capsule',
                'strength' => '500mg',
                'packaging' => 'Blister pack of 15 capsules',
                'prescription_required' => true,
                'volume_or_weight' => '15 capsules',
                'nutritional_info' => null,
                'flavour' => null,
                'storage_instructions' => 'Store below 25°C.',
                'added_by' => 1,
            ],
            [
                'name' => 'Vitamin C 1000mg Tablet',
                'category' => 'Supplement',
                'barcode' => '8901234567892',
                'nafdac_number' => '04-9876',
                'batch_number' => 'VC2025C',
                'expiry_date' => Carbon::now()->addYears(3),
                'manufacture_date' => Carbon::now()->subMonths(8),
                'manufacturer' => 'Nature Made',
                'country_of_origin' => 'USA',
                'image_url' => 'https://example.com/images/vitamin_c.jpg',
                'status' => 'available',
                'description' => 'Boosts immunity and helps in collagen formation.',
                'active_ingredient' => 'Ascorbic Acid',
                'dosage_form' => 'Tablet',
                'strength' => '1000mg',
                'packaging' => 'Bottle of 100 tablets',
                'prescription_required' => false,
                'volume_or_weight' => '100 tablets',
                'nutritional_info' => ['Vitamin C' => '1000mg'],
                'flavour' => 'Orange',
                'storage_instructions' => 'Keep tightly closed in a cool, dry place.',
                'added_by' => 1,
            ],
            // --- add 27 more products here in similar format ---
        ];

        // Just duplicate and tweak for 30 products
        foreach (range(4, 10) as $i) {
            $products[] = [
                'name' => 'Sample Product ' . $i,
                'category' => 'General',
                'barcode' => '89012345678' . $i,
                'nafdac_number' => '04-' . rand(1000, 9999),
                'batch_number' => 'BATCH' . strtoupper(Str::random(5)),
                'expiry_date' => Carbon::now()->addYears(rand(1, 3)),
                'manufacture_date' => Carbon::now()->subMonths(rand(1, 12)),
                'manufacturer' => 'Generic Pharma Ltd',
                'country_of_origin' => 'India',
                'image_url' => 'https://example.com/images/sample_' . $i . '.jpg',
                'status' => 'available',
                'description' => 'This is a placeholder product for testing.',
                'active_ingredient' => 'Ingredient ' . $i,
                'dosage_form' => 'Tablet',
                'strength' => rand(100, 1000) . 'mg',
                'packaging' => 'Pack of ' . rand(10, 30),
                'prescription_required' => (bool)rand(0, 1),
                'volume_or_weight' => rand(10, 100) . ' tablets',
                'nutritional_info' => ['Nutrient' => rand(10, 500) . 'mg'],
                'flavour' => null,
                'storage_instructions' => 'Store below 30°C.',
               // 'added_by' => 1,
            ];
        }

        Product::insert($products);

    }
}