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
        Schema::create('teacher_id', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 10)->primary(); 
            $table->string('name');
            $table->string('subject');
            $table->integer('present_times');
            $table->integer('absent_times');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_id');
    }
};
