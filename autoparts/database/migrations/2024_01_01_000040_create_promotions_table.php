<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('banner')->nullable();
            
            // Type
            $table->enum('type', [
                'flash_sale', 'daily_deal', 'weekly_deal', 'clearance',
                'buy_get', 'bundle', 'seasonal', 'holiday'
            ]);
            
            // Discount
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            
            // Validity
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            
            // Products
            $table->json('product_ids')->nullable();
            $table->json('category_ids')->nullable();
            
            // Limits
            $table->integer('quantity_limit')->nullable();
            $table->integer('per_user_limit')->nullable();
            $table->integer('times_used')->default(0);
            
            // Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
