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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id'); // same type as student_ids.student_id
            $table->string('teacher_id');
    // $table->string('subject');
    // $table->time('class_start');
    // $table->time('class_end');
    $table->enum('status', ['Present', 'Absent','Late'])->default('Absent');
    $table->date('date');
    $table->timestamps();
    $table->time('check_in')->nullable();

              // foreign key constraint with string
    $table->foreign('student_id')
    ->references('student_id')
    ->on('student_ids')
    ->onDelete('cascade');
    // $table->foreign('teacher_id')
    // ->references('id')
    // ->on('teacher_id')
    // ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
