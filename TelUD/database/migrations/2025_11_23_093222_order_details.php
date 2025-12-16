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
            
            
            $table->timestamps();

            $table->foreignId('fk_product')
                  ->constrained()
                  ->references('ProductID')->on('product')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreignId('fk_order')
                  ->constrained()
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