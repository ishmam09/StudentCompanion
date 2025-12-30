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
            $table->id();
            // Links the task to the student user
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('title');
            // Stores the priority quadrant (1, 2, 3, or 4)
            $table->integer('quadrant')->default(1); 
            $table->boolean('completed')->default(false);
            $table->timestamps();
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
