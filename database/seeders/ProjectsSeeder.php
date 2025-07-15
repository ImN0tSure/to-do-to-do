<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'url' => Str::random(10),
                'name' => 'Создание backend сайта',
                'description' => 'Создание сайта to-do листа. А точнее backend её части',
                'begin_date' => date('Y-m-d H:i:s'),
            ],
            [
                'url' => Str::random(10),
                'name' => 'Создание frontend сайта',
                'description' => 'Создание сайта to-do листа. А точнее frontend её части',
                'begin_date' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
