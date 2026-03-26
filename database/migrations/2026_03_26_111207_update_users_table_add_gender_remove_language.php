<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Gender column add karo (mobile ke baad)
            $table->enum('gender', ['male', 'female', 'other'])
                ->nullable()
                ->after('mobile');

            // ❌ preferred_language remove karo
            $table->dropColumn('preferred_language');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback: gender hato, preferred_language wapas lao
            $table->dropColumn('gender');
            $table->string('preferred_language', 10)->default('en')->after('mobile');
        });
    }
};
