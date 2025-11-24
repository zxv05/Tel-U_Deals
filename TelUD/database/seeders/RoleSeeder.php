<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);

        // Admin
        $admin = User::create([
            'name' => 'Admin Tel-U',
            'email' => 'admin@telu.com',
            'password' => bcrypt('password'), // <--- WAJIB PAKE BCRYPT
        ]);
        $admin->assignRole($roleAdmin);

        // User Bul
        $user = User::create([
            'name' => 'Bul Mahasiswa',
            'email' => 'bul@telu.com',
            'password' => bcrypt('bul123'), // <--- WAJIB PAKE BCRYPT
        ]);
        $user->assignRole($roleUser);
    }
}