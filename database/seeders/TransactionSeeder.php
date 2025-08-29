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
        
        for ($i = 0; $i < 25; $i++) {
            $product = $products->random();
            $quantity = rand(1, 25);
            $totalPrice = $quantity * $product->price_per_unit;
            $totalCost = $quantity * $product->cost_per_unit;
            $profit = $totalPrice - $totalCost;
            
            $transactions[] = [
                // 'product_id' => $product->id,
                // 'quantity' => $quantity,
                // 'total_price' => $totalPrice,
                // 'total_cost' => $totalCost,
                // 'profit' => $profit,
                // 'customer_name' => 'Customer ' . ($i + 1),
                // 'type' => $product->type,
                // 'created_at' => Carbon::now()->subDays(rand(0, 365)),
                // 'updated_at' => Carbon::now()->subDays(rand(0, 365)),
            ];
        }
        
        DB::table('transactions')->insert($transactions);
    }
}