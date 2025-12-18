<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission biar gak error
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Role Spatie
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);

        // 2. Buat User ADMIN
        $admin = User::create([
            'nama' => 'Admin Tel-U',
            'email' => 'admin@telu.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Sesuai enum baru
        ]);
        $admin->assignRole($roleAdmin);

        // 3. Buat User BIASA (Si Bul)
        $user = User::create([
            'nama' => 'Bul Mahasiswa',
            'email' => 'bul@telu.com',
            'password' => Hash::make('bul123'),
            'role' => 'user', // <--- NAH INI SEKARANG UDAH BISA 'user'
        ]);
        $user->assignRole($roleUser);
    }
}