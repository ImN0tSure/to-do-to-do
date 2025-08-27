<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
            ],
            [
                'email' => 'valera@admin.com',
                'password' => Hash::make('valera123'),
            ],
            [
                'email' => 'petya@admin.com',
                'password' => Hash::make('petya123'),
            ],
            [
                'email' => 'tanya@admin.com',
                'password' => Hash::make('tanya123'),
            ],
            [
                'email' => 'curator@admin.com',
                'password' => Hash::make('curator123'),
            ]
        ]);
    }
}
