<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->time('daily_reminder_time')->nullable();
            $table->decimal('weekly_budget_limit', 12, 2)->nullable();
            $table->decimal('monthly_budget_limit', 12, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn(['daily_reminder_time', 'weekly_budget_limit', 'monthly_budget_limit']);
        });
    }
};
