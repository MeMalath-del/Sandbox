<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('user_addresses')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            
            // Order Status
            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'ready_for_shipping',
                'shipped', 'out_for_delivery', 'delivered', 'completed',
                'cancelled', 'refunded', 'returned', 'failed'
            ])->default('pending');
            
            // Payment Info
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partial_refund'])->default('pending');
            $table->enum('payment_method', ['cash_on_delivery', 'credit_card', 'debit_card', 'wallet', 'bank_transfer', 'installment'])->default('cash_on_delivery');
            $table->string('payment_reference')->nullable();
            
            // Shipping Info
            $table->enum('shipping_method', ['standard', 'express', 'same_day', 'pickup'])->default('standard');
            $table->string('tracking_number')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Address Snapshot
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_region')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->decimal('shipping_latitude', 10, 8)->nullable();
            $table->decimal('shipping_longitude', 11, 8)->nullable();
            
            // Billing Info
            $table->string('billing_name')->nullable();
            $table->string('billing_company')->nullable();
            $table->string('billing_tax_number')->nullable();
            $table->text('billing_address')->nullable();
            
            // Amounts
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(15.00);
            $table->decimal('total', 12, 2);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->decimal('driver_fee', 12, 2)->default(0);
            
            // Points & Wallet
            $table->integer('points_earned')->default(0);
            $table->integer('points_used')->default(0);
            $table->decimal('wallet_amount_used', 12, 2)->default(0);
            
            // Notes
            $table->text('customer_notes')->nullable();
            $table->text('store_notes')->nullable();
            $table->text('driver_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Delivery Instructions
            $table->text('delivery_instructions')->nullable();
            $table->boolean('allow_leave_at_door')->default(false);
            $table->boolean('call_before_delivery')->default(false);
            
            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['store_id', 'status']);
            $table->index(['driver_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
