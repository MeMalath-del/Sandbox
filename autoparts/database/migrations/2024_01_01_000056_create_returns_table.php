<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['return', 'exchange'])->default('return');
            $table->enum('status', [
                'pending', 'approved', 'rejected', 'shipped',
                'received', 'inspecting', 'completed', 'cancelled'
            ])->default('pending');
            
            $table->enum('reason', [
                'defective', 'wrong_item', 'not_as_described', 'damaged',
                'changed_mind', 'better_price', 'other'
            ]);
            $table->text('reason_details')->nullable();
            $table->json('images')->nullable();
            
            // Refund
            $table->enum('refund_method', ['original_payment', 'wallet', 'bank_transfer'])->nullable();
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->enum('refund_status', ['pending', 'processing', 'completed', 'failed'])->nullable();
            
            // Shipping
            $table->string('tracking_number')->nullable();
            $table->string('shipping_carrier')->nullable();
            
            // Admin
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
