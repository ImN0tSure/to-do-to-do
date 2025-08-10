<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserInfosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_infos')->insert([
            [
                'user_id' => 1,
                'surname' => 'Adminov',
                'name' => 'Admin',
                'patronymic' => 'Adminovich',
                'avatar_img' => 'adminovich.png',
                'nickname' => 'Admin',
                'phone' => '5555555',
                'contact_email' => 'cont.admin@admin.com',
            ],
            [
                'user_id' => 2,
                'surname' => 'Valerov',
                'name' => 'Valera',
                'patronymic' => 'Valerievich',
                'avatar_img' => 'valera.png',
                'nickname' => 'Valera',
                'phone' => '6666666',
                'contact_email' => 'cont.valer@va.com',
            ],
            [
                'user_id' => 3,
                'surname' => 'Petrov',
                'name' => 'Petya',
                'patronymic' => 'Petrovich',
                'avatar_img' => 'petya.png',
                'nickname' => 'Petya',
                'phone' => '2222222',
                'contact_email' => 'cont.pety@pe.com',
            ],
            [
                'user_id' => 4,
                'surname' => 'Tatianova',
                'name' => 'Tatiana',
                'patronymic' => 'Tatianovna',
                'avatar_img' => 'tatiana.png',
                'nickname' => 'Tatiana',
                'phone' => '1111111',
                'contact_email' => 'cont.tati@ta.com',
            ],
        ]);
    }
}
