<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('user_id');
        $table->string('nama_barang');
        $table->unsignedBigInteger('kategori_id');
        $table->decimal('harga_product', 10, 2);
        $table->text('product_detail');
        $table->integer('stok');
        $table->timestamps();

        // Foreign Keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
