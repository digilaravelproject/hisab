<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Agar bills table mein ye columns missing hain to add karein

            if (!Schema::hasColumn('bills', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('bills', 'business_id')) {
                $table->foreignId('business_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained()
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('bills', 'title')) {
                $table->string('title')->after('business_id');
            }

            if (!Schema::hasColumn('bills', 'bill_type')) {
                $table->string('bill_type')->nullable()->after('title');
            }

            if (!Schema::hasColumn('bills', 'amount')) {
                $table->decimal('amount', 12, 2)->nullable()->after('bill_type');
            }

            if (!Schema::hasColumn('bills', 'bill_date')) {
                $table->date('bill_date')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('bills', 'month')) {
                $table->tinyInteger('month')->nullable()->after('bill_date');
            }

            if (!Schema::hasColumn('bills', 'year')) {
                $table->smallInteger('year')->nullable()->after('month');
            }

            if (!Schema::hasColumn('bills', 'file_path')) {
                $table->string('file_path')->nullable()->after('year');
            }

            if (!Schema::hasColumn('bills', 'file_type')) {
                $table->string('file_type', 20)->nullable()->after('file_path');
            }

            if (!Schema::hasColumn('bills', 'notes')) {
                $table->text('notes')->nullable()->after('file_type');
            }

            // SoftDeletes column
            if (!Schema::hasColumn('bills', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['business_id']);
            $table->dropColumn([
                'user_id',
                'business_id',
                'title',
                'bill_type',
                'amount',
                'bill_date',
                'month',
                'year',
                'file_path',
                'file_type',
                'notes',
                'deleted_at'
            ]);
        });
    }
};
