<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Product;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada produk terlebih dahulu
        if (Product::count() === 0) {
            $this->createDefaultProducts();
        }
        
        $products = Product::all();
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentMethods = ['cash', 'transfer', 'credit_card', 'debit_card'];
        
        $purchases = [];

        for ($i = 0; $i < 20; $i++) {
            $product = $products->random();
            $quantity = rand(1, 10);
            
            // Pastikan harga produk valid
            $productPrice = $product->price > 0 ? $product->price : rand(10000, 1000000);
            $totalPrice = $productPrice * $quantity;
            
            $status = $statuses[array_rand($statuses)];
            $paidAt = $status === 'completed' ? Carbon::now()->subDays(rand(1, 365)) : null;

            $purchases[] = [
                'customer_name' => 'Customer ' . ($i + 1),
                'customer_email' => 'customer' . ($i + 1) . '@contohohoh.com',
                'customer_phone' => '08' . rand(100000000, 999999999),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'paid_at' => $paidAt,
                'status' => $status,
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(0, 365)),
            ];
        }

        foreach (array_chunk($purchases, 10) as $chunk) {
            Purchase::insert($chunk);
        }

        $this->command->info('Seeder Purchase berhasil ditambahkan: ' . count($purchases) . ' data');
    }

    protected function createDefaultProducts()
    {
        $products = [
            ['name' => 'Laptop ASUS', 'price' => 8500000],
            ['name' => 'Smartphone Samsung', 'price' => 4500000],
            ['name' => 'Tablet iPad', 'price' => 6500000],
            ['name' => 'Monitor LG 24"', 'price' => 2500000],
            ['name' => 'Keyboard Mechanical', 'price' => 800000],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}