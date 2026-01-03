<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->renameColumn('condition', 'product_condition');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->renameColumn('product_condition', 'condition');
    });
}

};
