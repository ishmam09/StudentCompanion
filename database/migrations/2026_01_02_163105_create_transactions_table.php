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
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                // Add Expense Tracker Fields if missing
                if (!Schema::hasColumn('transactions', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('transactions', 'category_id')) {
                    $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
                }
                if (!Schema::hasColumn('transactions', 'description')) {
                    $table->string('description')->nullable();
                }
                if (!Schema::hasColumn('transactions', 'type')) {
                    $table->string('type')->default('expense'); // expense, income, purchase, sale
                }
                if (!Schema::hasColumn('transactions', 'transaction_date')) {
                    $table->date('transaction_date')->nullable();
                }
                if (!Schema::hasColumn('transactions', 'notes')) {
                    $table->text('notes')->nullable();
                }
                if (!Schema::hasColumn('transactions', 'is_recurring')) {
                    $table->boolean('is_recurring')->default(false);
                }
                if (!Schema::hasColumn('transactions', 'recurring_frequency')) {
                    $table->string('recurring_frequency')->nullable();
                }

                // Add Marketplace Fields if missing
                if (!Schema::hasColumn('transactions', 'buyer_id')) {
                    $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('transactions', 'seller_id')) {
                    $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('transactions', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
                }
            });
        } else {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();

                // Expense Tracker Fields
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // Assuming categories table exists
                $table->string('description')->nullable();
                $table->decimal('amount', 10, 2); // Shared
                $table->string('type')->default('expense');
                $table->date('transaction_date')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_recurring')->default(false);
                $table->string('recurring_frequency')->nullable();

                // Marketplace Fields
                $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We probably don't want to drop the whole table if it existed before, 
        // but for this specific migration file which claims to 'create' it, 
        // standard behavior is drop.
        Schema::dropIfExists('transactions');
    }
};
