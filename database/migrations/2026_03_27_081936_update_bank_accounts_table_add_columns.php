<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('bank_name')->after('user_id');
            $table->string('account_holder_name')->after('bank_name');
            $table->string('account_number', 30)->after('account_holder_name');
            $table->string('ifsc_code', 15)->nullable()->after('account_number');
            $table->string('account_type')->after('ifsc_code');    // e.g. savings, current
            $table->string('business_type')->nullable()->after('account_type'); // e.g. personal, farm, shop
            $table->boolean('is_primary')->default(false)->after('business_type');
            $table->boolean('auto_tag')->default(false)->after('is_primary');

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn([
                'user_id',
                'bank_name',
                'account_holder_name',
                'account_number',
                'ifsc_code',
                'account_type',
                'business_type',
                'is_primary',
                'auto_tag',
            ]);
        });
    }
};
