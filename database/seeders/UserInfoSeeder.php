<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_info')->insert([
            [
                'user_id' => 1,
                'surname' => 'Adminov',
                'name' => 'Admin',
                'patronymic' => 'Adminovich',
                'avatar_img' => 'adminovich.png',
                'nickname' => 'Admin',
                'phone' => '5555555',
            ],
            [
                'user_id' => 2,
                'surname' => 'Valerov',
                'name' => 'Valera',
                'patronymic' => 'Valerievich',
                'avatar_img' => 'valera.png',
                'nickname' => 'Valera',
                'phone' => '6666666',
            ],
            [
                'user_id' => 3,
                'surname' => 'Petrov',
                'name' => 'Petya',
                'patronymic' => 'Petrovich',
                'avatar_img' => 'petya.png',
                'nickname' => 'Petya',
                'phone' => '2222222',
            ],
            [
                'user_id' => 4,
                'surname' => 'Tatianova',
                'name' => 'Tatiana',
                'patronymic' => 'Tatianovna',
                'avatar_img' => 'tatiana.png',
                'nickname' => 'Tatiana',
                'phone' => '1111111',
            ],
        ]);
    }
}
