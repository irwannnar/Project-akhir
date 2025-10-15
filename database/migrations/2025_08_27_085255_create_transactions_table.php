<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        // Tabel header untuk transaksi
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique()->after('id'); 

            // Data pelanggan
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();

            // Keuangan
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->decimal('profit', 15, 2)->nullable();

            // Pembayaran
            $table->string('payment_method')->default('cash');
            $table->timestamp('paid_at')->nullable();

            // Status & tipe transaksi
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('type');

            $table->text('notes')->nullable();

            $table->timestamps();
        });

        // Tabel detail untuk items dalam transaksi
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            
            // Relasi ke produk atau jasa
            $table->foreignId('product_id')->nullable()->constrained();
            $table->foreignId('printing_id')->nullable()->constrained();

            // Detail item
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);

            // Untuk layanan printing
            $table->integer('tinggi')->nullable();
            $table->integer('lebar')->nullable();
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();

            // Untuk tracking cost dan profit per item
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('profit', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
}