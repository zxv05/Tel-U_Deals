<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Menambahkan kolom tanggal_lahir dan phone setelah email
        $table->date('tanggal_lahir')->nullable()->after('email');
        $table->string('phone', 15)->nullable()->after('tanggal_lahir');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Menghapus kolom jika migration di-rollback
        $table->dropColumn(['tanggal_lahir', 'phone']);
    });
}
};
