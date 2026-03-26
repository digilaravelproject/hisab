<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // id aur timestamps pehle se hain, baaki sab add karo
            $table->foreignId('user_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->after('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('target_amount', 12, 2)->after('category_id');
            $table->tinyInteger('month')->after('target_amount');  // 1-12
            $table->year('year')->after('month');

            // Indexes
            $table->unique(['user_id', 'category_id', 'month', 'year'], 'budgets_unique');
            $table->index(['user_id', 'month', 'year'], 'budgets_user_month_year');
        });
    }

    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropUnique('budgets_unique');
            $table->dropIndex('budgets_user_month_year');
            $table->dropForeign(['category_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'category_id', 'target_amount', 'month', 'year']);
        });
    }
};
