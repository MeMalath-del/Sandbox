<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            
            // Discount Type
            $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping', 'free_product']);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_order_amount', 10, 2)->nullable();
            
            // Validity
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Usage Limits
            $table->integer('total_usage_limit')->nullable();
            $table->integer('per_user_limit')->default(1);
            $table->integer('times_used')->default(0);
            
            // Restrictions
            $table->boolean('first_order_only')->default(false);
            $table->boolean('new_users_only')->default(false);
            $table->json('applicable_products')->nullable();
            $table->json('applicable_categories')->nullable();
            $table->json('applicable_brands')->nullable();
            $table->json('excluded_products')->nullable();
            $table->json('applicable_users')->nullable();
            $table->json('applicable_payment_methods')->nullable();
            $table->json('applicable_regions')->nullable();
            
            // Settings
            $table->boolean('is_combinable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['code', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
