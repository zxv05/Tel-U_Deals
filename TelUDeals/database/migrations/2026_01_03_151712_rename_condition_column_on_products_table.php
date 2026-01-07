<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Rename HANYA kalau:
            // - kolom lama ADA
            // - kolom baru BELUM ADA
            if (
                Schema::hasColumn('products', 'condition') &&
                !Schema::hasColumn('products', 'product_condition')
            ) {
                $table->renameColumn('condition', 'product_condition');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (
                Schema::hasColumn('products', 'product_condition') &&
                !Schema::hasColumn('products', 'condition')
            ) {
                $table->renameColumn('product_condition', 'condition');
            }
        });
    }
};
