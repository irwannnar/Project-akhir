<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
    }
};