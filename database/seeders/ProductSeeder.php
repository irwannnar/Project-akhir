<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $products = [
            // [
            //     'name' => 'Sablon baju Putih',
            //     'type' => 'Sablon',
            //     'price_per_unit' => 30000,
            //     'cost_per_unit' => 10000,
            //     'descriptions' => 'Sablon baju putih '
            // ],
            // [
            //     'name' => 'Fotocopy Warna A4',
            //     'type' => 'fotocopy',
            //     'price_per_unit' => 1500,
            //     'cost_per_unit' => 800,
            //     'descriptions' => 'Fotocopy berwarna '
            // ],
            // [
            //     'name' => 'Print Hitam Putih A4',
            //     'type' => 'print',
            //     'price_per_unit' => 500,
            //     'cost_per_unit' => 200,
            //     'descriptions' => 'Print dokumen hitam putih ukuran A4'
            // ],
            // [
            //     'name' => 'Print Warna A4',
            //     'type' => 'print',
            //     'price_per_unit' => 2000,
            //     'cost_per_unit' => 1200,
            //     'descriptions' => 'Print dokumen berwarna ukuran A4'
            // ],
            // [
            //     'name' => 'Cetak baliho 3x4 meter',
            //     'type' => 'Printing',
            //     'price_per_unit' => 300000,
            //     'cost_per_unit' => 30000,
            //     'descriptions' => 'Cetak baliho ukuran 3x4 meter'
            // ],
            // [
            //     'name' => 'Sablon baju one piece Hitam',
            //     'type' => 'Sablon',
            //     'price_per_unit' => 30000,
            //     'cost_per_unit' => 10000,
            //     'descriptions' => 'Sablon baju one piece Hitam'
            // ],
            // [
            //     'name' => 'Laminating A4',
            //     'type' => 'laminating',
            //     'price_per_unit' => 7000,
            //     'cost_per_unit' => 3000,
            //     'descriptions' => 'Laminating dokumen ukuran A4'
            // ],
        ];

        DB::table('products')->insert($products);
    }
}
