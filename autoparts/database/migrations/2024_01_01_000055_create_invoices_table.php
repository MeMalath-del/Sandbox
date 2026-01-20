<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['sales', 'refund', 'credit_note', 'debit_note'])->default('sales');
            
            // Seller Info
            $table->string('seller_name');
            $table->string('seller_tax_number')->nullable();
            $table->text('seller_address')->nullable();
            
            // Buyer Info
            $table->string('buyer_name');
            $table->string('buyer_tax_number')->nullable();
            $table->text('buyer_address')->nullable();
            
            // Amounts
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(15);
            $table->decimal('total', 12, 2);
            
            // QR Code for ZATCA
            $table->text('qr_code')->nullable();
            $table->string('zatca_uuid')->nullable();
            $table->string('zatca_hash')->nullable();
            
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
