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
    Schema::create('orders', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('user_id'); // Pembeli
        $table->string('order_id', 32)->unique();
        $table->timestamps();
        $table->decimal('total_price', 10, 2); // Total harga dari order
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');


        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
