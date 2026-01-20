<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            
            // Status
            $table->enum('status', [
                'pending', 'assigned', 'accepted', 'rejected',
                'picking_up', 'picked_up', 'in_transit',
                'arrived', 'delivered', 'failed', 'returned', 'cancelled'
            ])->default('pending');
            
            // Pickup Info
            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            $table->timestamp('pickup_time')->nullable();
            $table->string('pickup_code')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            
            // Delivery Info
            $table->string('delivery_address');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->timestamp('expected_delivery_time')->nullable();
            $table->string('delivery_code')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Tracking
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_updated_at')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('estimated_minutes')->nullable();
            
            // Proof of Delivery
            $table->string('delivery_photo')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('recipient_name')->nullable();
            
            // Fees
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('driver_earning', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('tip_amount', 10, 2)->default(0);
            
            // Notes
            $table->text('driver_notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Attempts
            $table->integer('delivery_attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['driver_id', 'status']);
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
