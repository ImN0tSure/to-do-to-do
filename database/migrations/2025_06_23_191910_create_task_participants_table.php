<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_participants', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('task_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('status')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_participants');
    }
};
