<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->string('mobile', 15)->index()->after('id');
            $table->string('otp')->after('mobile');
            $table->timestamp('expires_at')->after('otp');
        });
    }

    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'otp', 'expires_at']);
        });
    }
};
