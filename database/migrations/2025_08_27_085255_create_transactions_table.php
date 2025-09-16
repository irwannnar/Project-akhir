<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
    $table->id();

    // Relasi ke produk atau jasa
    $table->foreignId('product_id')->nullable()->constrained(); // untuk purchases
    $table->foreignId('printing_id')->nullable()->constrained(); // untuk orders

    // Data pelanggan
    $table->string('customer_name')->nullable();
    $table->string('customer_phone')->nullable();
    $table->string('customer_email')->nullable();
    $table->text('customer_address')->nullable();

    // Detail pesanan
    $table->string('material')->nullable();
    $table->integer('quantity');
    $table->decimal('width', 8, 2)->nullable();
    $table->decimal('height', 8, 2)->nullable();
    $table->text('notes')->nullable();
    $table->string('file_path')->nullable();

    // Keuangan
    $table->decimal('total_price', 15, 2);
    $table->decimal('total_cost', 15, 2)->nullable();
    $table->decimal('profit', 15, 2)->nullable();

    // Pembayaran
    $table->string('payment_method')->default('cash');
    $table->timestamp('paid_at')->nullable();

    // Status & tipe transaksi
    $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
    $table->string('type')->default('order'); // order / purchase

    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}