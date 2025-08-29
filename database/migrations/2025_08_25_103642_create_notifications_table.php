<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('notifiable_type'); //Модель, которая используется для доступа к уведомлениям
            $table->string('notifiable_id');
            $table->string('event'); // Тип уведомления.
            $table->string('event_type'); // Подтип уведомления.
            $table->dateTime('read_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
