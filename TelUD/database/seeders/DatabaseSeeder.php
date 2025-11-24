<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nah, ini kuncinya Bang! Kita panggil RoleSeeder di sini.
        $this->call([
            RoleSeeder::class,
        ]);
    }
}