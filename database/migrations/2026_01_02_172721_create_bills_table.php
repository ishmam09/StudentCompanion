<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('frequency'); // 'monthly', 'yearly', 'quarterly'
            $table->boolean('is_automatic')->default(false);
            $table->boolean('reminder_enabled')->default(true);
            $table->integer('reminder_days_before')->default(3);
            $table->string('status')->default('pending'); // 'pending', 'paid', 'overdue'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bills');
    }
};
