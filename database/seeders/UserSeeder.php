<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 user dengan 3 role berbeda
        DB::table('users')->insert([
            [
                'name' => 'Sofyan',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Bima',
                'email' => 'admin2@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Reyhan',
                'email' => 'manajer@gmail.com',
                'role' => 'manajer',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Faris',
                'email' => 'manajer2@gmail.com',
                'role' => 'manajer',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Okin',
                'email' => 'spv@gmail.com',
                'role' => 'spv',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Farhan',
                'email' => 'spv2@gmail.com',
                'role' => 'spv',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Andika Pratama',
                'email' => 'staff@gmail.com',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ibrahim',
                'email' => 'staff2@gmail.com',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
