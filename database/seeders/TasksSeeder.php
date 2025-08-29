<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'name' => 'Установить Laravel',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() - 60 * 60 * 24),
                'priority' => 2,
                'in_progress' => false,
                'tasklist_id' => 3,
            ],
            [
                'name' => 'Разработать таблицы для БД',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 2),
                'priority' => 2,
                'in_progress' => true,
                'tasklist_id' => 2,
            ],
            [
                'name' => 'Создать миграции',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 5),
                'priority' => 1,
                'in_progress' => true,
                'tasklist_id' => 2,
            ],
            [
                'name' => 'Создать сиды',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 5),
                'priority' => 2,
                'in_progress' => true,
                'tasklist_id' => 2,
            ],
            [
                'name' => 'Разработать модели',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 8),
                'priority' => 2,
                'in_progress' => true,
                'tasklist_id' => 1,
            ],
            [
                'name' => 'Разработать контроллеры',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 12),
                'priority' => 2,
                'in_progress' => true,
                'tasklist_id' => 1,
            ],
            [
                'name' => 'Установить node js',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 1),
                'priority' => 2,
                'in_progress' => true,
                'tasklist_id' => 5,
            ],
            [
                'name' => 'Установить view js',
                'description' => Str::random(50),
                'begin_date' => date('Y-m-d H:i', time() - 60 * 60 * 24 * 2),
                'end_date' => date('Y-m-d H:i', time() + 60 * 60 * 24 * 2),
                'priority' => 2,
                'in_progress' => true,
                'tasklist_id' => 4,
            ],

        ]);
    }
}
