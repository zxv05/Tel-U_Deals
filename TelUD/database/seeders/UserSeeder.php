<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'), // Simpan password yang sudah dienkripsi
            ]);

        User::create([
            'nama' => 'User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'), // Simpan password yang sudah dienkripsi
            ]);
    }
}
