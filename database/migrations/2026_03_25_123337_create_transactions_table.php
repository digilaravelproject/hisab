<?php

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained();
            $table->foreignId('business_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->enum('type', ['credit', 'debit']);
            $table->enum('source', ['bank', 'upi', 'cash']);
            $table->decimal('amount', 12, 2);
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->boolean('is_categorized')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'transaction_date']);
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
