<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id('ProductID');
            
            $table->unsignedBigInteger('fk_user');
            $table->unsignedBigInteger('fk_kategori');
            
            $table->string('NamaBarang', 191);
            $table->decimal('HargaProduct', 12, 2)->default(0.00);
            $table->text('ProductDetail')->nullable();
            
            $table->timestamps();

            $table->foreign('fk_user')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('fk_kategori')
                  ->references('KategoriID')->on('kategori')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
};