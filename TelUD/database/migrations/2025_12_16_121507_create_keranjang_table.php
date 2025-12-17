<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_user');
            $table->unsignedBigInteger('fk_product'); 
            $table->integer('quantity')->default(1); 
            $table->decimal('total_price', 10, 2); 
            $table->timestamps(); 

            $table->foreign('fk_User')->references('IdUser')->on('users');
            $table->foreign('fk_product')->references('ProductID')->on('product');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
