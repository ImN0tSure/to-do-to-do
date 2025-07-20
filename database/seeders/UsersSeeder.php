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
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'valera@admin.com',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'petya@admin.com',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'tanya@admin.com',
                'password' => Hash::make('password'),
            ]
        ]);
    }
}
