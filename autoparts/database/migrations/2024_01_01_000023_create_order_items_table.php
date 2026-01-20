<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Product Snapshot
            $table->string('product_name');
            $table->string('product_sku');
            $table->string('product_image')->nullable();
            
            // Quantities & Prices
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled', 'returned', 'refunded'])->default('pending');
            
            // Return/Refund
            $table->integer('returned_quantity')->default(0);
            $table->decimal('refunded_amount', 12, 2)->default(0);
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
