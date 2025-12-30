<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('virtual_classrooms', function (Blueprint $table) {
        $table->id();
        $table->string('course_code'); // Changed from foreignId/course_id to string input
        $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); 
        $table->string('classroom_name'); // Renamed from session_title
        $table->string('class_code')->unique(); // 5-digit unique code
        $table->string('room_id')->unique(); // Jitsi Slug
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_classrooms');
    }
};
