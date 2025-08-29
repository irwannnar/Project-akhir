<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrintingServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Digital Printing',
                'type' => 'digital',
                'description' => 'Cetak digital berkualitas tinggi dengan teknologi terbaru untuk hasil yang maksimal.',
                'base_price' => 5000,
                'price_per_page' => 500,
                'min_order' => 1,
                'max_order' => 1000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Screen Printing',
                'type' => 'screen',
                'description' => 'Cetak sablon untuk berbagai media dengan tahan lama dan warna yang tajam.',
                'base_price' => 15000,
                'price_per_page' => 1200,
                'min_order' => 10,
                'max_order' => 500,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Offset Printing',
                'type' => 'offset',
                'description' => 'Cetak offset untuk kebutuhan percetakan dalam jumlah besar dengan kualitas terbaik.',
                'base_price' => 20000,
                'price_per_page' => 300,
                'min_order' => 100,
                'max_order' => 10000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sublimation Printing',
                'type' => 'sublimation',
                'description' => 'Cetak sublimasi untuk bahan polyester dengan hasil yang tahan lama dan tidak luntur.',
                'base_price' => 25000,
                'price_per_page' => 2000,
                'min_order' => 1,
                'max_order' => 100,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('printing_services')->insert($services);
    }
}
