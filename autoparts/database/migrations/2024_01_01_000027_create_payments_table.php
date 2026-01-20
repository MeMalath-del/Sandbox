<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Payment Details
            $table->enum('payment_type', ['order', 'wallet_topup', 'subscription', 'refund']);
            $table->enum('payment_method', [
                'cash', 'credit_card', 'debit_card', 'mada', 'apple_pay',
                'stc_pay', 'bank_transfer', 'wallet', 'tamara', 'tabby'
            ]);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            
            // Amounts
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('currency')->default('SAR');
            
            // Gateway Info
            $table->string('gateway')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->json('gateway_response')->nullable();
            
            // Card Info (masked)
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            
            // Refund Info
            $table->foreignId('refund_of')->nullable()->constrained('payments');
            $table->text('refund_reason')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable();
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
