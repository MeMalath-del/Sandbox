<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            
            // Basic Info
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            
            // Identifiers
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->string('oem_number')->nullable(); // Original Equipment Manufacturer number
            $table->json('alternative_numbers')->nullable(); // Alternative part numbers
            
            // Manufacturer Info
            $table->string('manufacturer')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->year('manufacture_year')->nullable();
            $table->string('quality_grade')->nullable(); // OEM, OES, Aftermarket
            
            // Condition
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('new');
            $table->text('condition_notes')->nullable();
            
            // Pricing
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->decimal('company_price', 12, 2)->nullable();
            $table->timestamp('sale_starts_at')->nullable();
            $table->timestamp('sale_ends_at')->nullable();
            
            // Inventory
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->integer('min_order_quantity')->default(1);
            $table->integer('max_order_quantity')->nullable();
            $table->boolean('allow_backorder')->default(false);
            $table->date('backorder_availability')->nullable();
            
            // Dimensions & Weight
            $table->decimal('weight', 10, 3)->nullable(); // in kg
            $table->decimal('length', 10, 2)->nullable(); // in cm
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            
            // Warranty
            $table->integer('warranty_months')->nullable();
            $table->text('warranty_terms')->nullable();
            
            // Return Policy
            $table->integer('return_days')->nullable();
            $table->text('return_policy')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Status & Visibility
            $table->enum('status', ['draft', 'pending', 'active', 'rejected', 'archived'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            
            // Statistics
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->integer('wishlist_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'store_id']);
            $table->index(['category_id', 'status']);
            $table->index(['brand_id', 'status']);
            $table->index(['name']);
            $table->index(['sku']);
            $table->index(['oem_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
