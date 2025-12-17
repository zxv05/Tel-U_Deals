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
            'Nama' => 'Admin Tel-U',
            'Email' => 'admin@telu.com',
            'Password' => Hash::make('password'),
            'Role' => 'admin', // Sesuai enum baru
        ]);
        $admin->assignRole($roleAdmin);

        // 3. Buat User BIASA (Si Bul)
        $user = User::create([
            'Nama' => 'Bul Mahasiswa',
            'Email' => 'bul@telu.com',
            'Password' => Hash::make('bul123'),
            'Role' => 'user', // <--- NAH INI SEKARANG UDAH BISA 'user'
        ]);
        $user->assignRole($roleUser);
    }
}