<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $transactions = [];
        $products = DB::table('products')->get();
        $printings = DB::table('printings')->get();
        
        // Data untuk materials
        $materials = ['PLA', 'ABS', 'PETG', 'TPU', 'Resin'];
        
        // Data untuk payment methods
        $paymentMethods = ['cash', 'transfer', 'kartu_redit'];
        
        // Data untuk status
        $statuses = ['pending','completed'];
        
        for ($i = 0; $i < 25; $i++) {
            // Tentukan apakah ini order atau purchase
            $isOrder = rand(0, 1); // 0 untuk purchase, 1 untuk order
            $quantity = rand(1, 25);
            
            if ($isOrder) {
                // ORDER (menggunakan printing)
                if ($printings->count() > 0) {
                    $printing = $printings->random();
                    $totalPrice = $quantity * $printing->biaya;
                    
                    $transactions[] = [
                        'printing_id' => $printing->id,
                        'product_id' => null,
                        'customer_name' => 'Customer ' . ($i + 1),
                        'customer_phone' => '08' . rand(100000000, 999999999),
                        'customer_email' => 'customer' . ($i + 1) . '@example.com',
                        'customer_address' => 'Alamat Customer ' . ($i + 1),
                        'material' => $materials[array_rand($materials)],
                        'quantity' => $quantity,
                        'tinggi' => rand(10, 100) . ' cm',
                        'lebar' => rand(10, 100) . ' cm',
                        'notes' => 'Catatan untuk order ' . ($i + 1),
                        'file_path' => 'files/order_' . ($i + 1) . '.stl',
                        'total_price' => $totalPrice,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'paid_at' => rand(0, 1) ? Carbon::now()->subDays(rand(0, 30)) : null,
                        'status' => $statuses[array_rand($statuses)],
                        'type' => 'order',
                        'created_at' => Carbon::now()->subDays(rand(0, 365)),
                        'updated_at' => Carbon::now()->subDays(rand(0, 365)),
                    ];
                }
            } else {
                // PURCHASE (menggunakan product)
                if ($products->count() > 0) {
                    $product = $products->random();
                    $totalPrice = $quantity * $product->price_per_unit;
                    
                    $transactions[] = [
                        'product_id' => $product->id,
                        'printing_id' => null,
                        'customer_name' => 'Supplier ' . ($i + 1),
                        'customer_phone' => '08' . rand(100000000, 999999999),
                        'customer_email' => 'supplier' . ($i + 1) . '@example.com',
                        'customer_address' => 'Alamat Supplier ' . ($i + 1),
                        'material' => null,
                        'quantity' => $quantity,
                        'tinggi' => null,
                        'lebar' => null,
                        'notes' => 'Catatan untuk purchase ' . ($i + 1),
                        'file_path' => null,
                        'total_price' => $totalPrice,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'paid_at' => rand(0, 1) ? Carbon::now()->subDays(rand(0, 30)) : null,
                        'status' => $statuses[array_rand($statuses)],
                        'type' => 'purchase',
                        'created_at' => Carbon::now()->subDays(rand(0, 365)),
                        'updated_at' => Carbon::now()->subDays(rand(0, 365)),
                    ];
                }
            }
        }
        
        // Jika tidak ada data printing, buat semua sebagai purchase
        if ($printings->count() === 0) {
            foreach ($transactions as &$transaction) {
                if ($transaction['type'] === 'order') {
                    $product = $products->random();
                    $transaction['product_id'] = $product->id;
                    $transaction['printing_id'] = null;
                    $transaction['type'] = 'purchase';
                    $transaction['material'] = null;
                    $transaction['tinggi'] = null;
                    $transaction['lebar'] = null;
                    $transaction['file_path'] = null;
                }
            }
        }
        
        DB::table('transactions')->insert($transactions);
    }
}