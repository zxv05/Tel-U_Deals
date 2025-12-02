<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('IdUser'); 
            $table->string('Nama', 191);
            $table->string('Password', 191);
            $table->string('Email', 191)->unique();
            $table->enum('Role', ['user', 'admin'])->default('user');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
