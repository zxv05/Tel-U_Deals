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
    Schema::create('product', function (Blueprint $table) {
        $table->bigIncrements('ProductID');
        $table->unsignedBigInteger('fk_user');
        $table->string('NamaBarang');
        $table->unsignedBigInteger('fk_kategori');
        $table->decimal('HargaProduct', 10, 2);
        $table->text('ProductDetail');
        $table->timestamps();

        // Foreign Keys
        $table->foreign('fk_user')->references('IdUser')->on('users')->onDelete('cascade');
        $table->foreign('fk_kategori')->references('KategoriID')->on('kategori')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
