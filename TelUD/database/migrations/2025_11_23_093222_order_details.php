<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('OrderDetailsID');
            
            $table->dateTime('Date')->useCurrent();
            
            $table->unsignedBigInteger('fk_User')->nullable();
            $table->unsignedBigInteger('fk_product');
            $table->unsignedBigInteger('fk_order');
            
            $table->timestamps();

            $table->foreign('fk_User')
                  ->references('IdUser')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->foreign('fk_product')
                  ->references('ProductID')->on('product')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('fk_order')
                  ->references('OrderID')->on('orders')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};