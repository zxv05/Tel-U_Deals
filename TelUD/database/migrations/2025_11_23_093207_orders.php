<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID');
            
            $table->unsignedBigInteger('fk_User')->nullable();
            
            $table->timestamps();

            $table->foreign('fk_User')
                  ->references('IdUser')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};