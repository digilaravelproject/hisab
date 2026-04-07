<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('is_categorized');
            }
            if (!Schema::hasColumn('transactions', 'receipt_type')) {
                $table->string('receipt_type', 50)->nullable()->after('receipt_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'receipt_path')) {
                $table->dropColumn('receipt_path');
            }
            if (Schema::hasColumn('transactions', 'receipt_type')) {
                $table->dropColumn('receipt_type');
            }
        });
    }
};