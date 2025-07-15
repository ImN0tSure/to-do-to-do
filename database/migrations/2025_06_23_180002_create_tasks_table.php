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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->text('description');
            $table->timestamp('begin_date');
            $table->timestamp('end_date');
            $table->integer('priority');
            $table->boolean('in_progress')->default(true);
            $table->foreignId('list_id')->constrained('lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
