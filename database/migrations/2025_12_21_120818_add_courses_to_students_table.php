[I<?php

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
Schema::table('students', function (Blueprint $table) {
    $table->json('courses')->nullable(); // Example: [{"code":"CSE110","title":"Programming I","credit":3,"grade_point":3.5}, ...]
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
