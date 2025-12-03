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
            
            $table->string('NamaBarang', 191);
            $table->decimal('HargaProduct', 12, 2)->default(0.00);
            $table->text('ProductDetail')->nullable();
            
            $table->timestamps();

            $table->foreignId('fk_user')
                  ->references('IdUser')->on('users')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreignId('fk_kategori')
                  ->references('KategoriID')->on('kategori')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
};