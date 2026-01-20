<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->string('license_number')->nullable();
            $table->string('license_type')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('license_image')->nullable();
            $table->enum('driver_type', ['individual', 'company'])->default('individual');
            $table->enum('status', ['pending', 'available', 'busy', 'offline', 'suspended'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_updated_at')->nullable();
            $table->json('service_areas')->nullable();
            $table->json('working_hours')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('total_deliveries')->default(0);
            $table->integer('completed_deliveries')->default(0);
            $table->integer('cancelled_deliveries')->default(0);
            $table->decimal('total_earnings', 15, 2)->default(0);
            $table->decimal('wallet_balance', 12, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(15.00);
            $table->boolean('accepts_cash')->default(true);
            $table->integer('max_orders_per_day')->default(20);
            $table->integer('current_orders_today')->default(0);
            $table->boolean('auto_accept')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
