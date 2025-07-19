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
        Schema::create('tasklists', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->foreignId('project_id')->constrained('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasklists');
    }
};
