<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            [
                'user_id' => 1,
                'task_id' => 1,
                'date' => date('Y-m-d H:m:s', time() - 86400),
                'comment' => 'Добавить таблицу с комментариями',
            ],
            [
                'user_id' => 2,
                'task_id' => 1,
                'date' => date('Y-m-d H:m:s', time() - 43200),
                'comment' => 'Добавил',
            ],
        ]);
    }
}
