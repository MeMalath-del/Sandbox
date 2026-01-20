<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id']);
            $table->index(['session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
