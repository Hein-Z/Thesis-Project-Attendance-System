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
       
        Schema::create('teachers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
        $table->id();
        $table->string('teacher_id', 10); // must match length/type of parent table
        $table->timestamp('check_in')->nullable();
        $table->timestamp('check_out')->nullable();
        $table->string('checkout_type')->default('In Class');
        $table->timestamps();

        $table->foreign('teacher_id')
            ->references('id')
            ->on('teacher_id')
            ->onDelete('cascade');
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
