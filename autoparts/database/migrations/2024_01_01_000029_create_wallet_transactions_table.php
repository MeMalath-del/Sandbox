<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Transaction Type
            $table->enum('type', [
                'topup', 'withdrawal', 'payment', 'refund', 'transfer_in',
                'transfer_out', 'commission', 'bonus', 'cashback', 'adjustment'
            ]);
            
            // Amounts
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            
            // Status
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            
            // References
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Notes
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['wallet_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
