<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintingServicesTable extends Migration
{
    public function up()
    {
        Schema::create('printing_services', function (Blueprint $table) {
            $table->id(); // Kolom ini akan menjadi primary key
            $table->string('name');
            $table->string('type')->unique(); // digital, screen, offset, sublimation
            $table->text('description');
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('price_per_page', 10, 2)->default(0);
            $table->integer('min_order')->default(1);
            $table->integer('max_order')->default(100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('printing_services');
    }
}
