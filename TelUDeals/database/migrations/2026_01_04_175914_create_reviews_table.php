<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel orders
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Menghubungkan ke tabel users (pembeli)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            // Menghubungkan ke tabel products
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            
            $table->integer('rating'); // Skor 1-5 bintang
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};