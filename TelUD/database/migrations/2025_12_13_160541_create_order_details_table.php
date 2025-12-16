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
    Schema::create('order_details', function (Blueprint $table) {
        $table->bigIncrements('OrderDetailsID');
        $table->dateTime('Date');
        $table->unsignedBigInteger('fk_User'); // User (sesuai ERD)
        $table->unsignedBigInteger('fk_product');
        $table->unsignedBigInteger('fk_order');
        $table->timestamps();

        $table->foreign('fk_User')->references('IdUser')->on('users');
        $table->foreign('fk_product')->references('ProductID')->on('product');
        $table->foreign('fk_order')->references('OrderID')->on('orders')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
