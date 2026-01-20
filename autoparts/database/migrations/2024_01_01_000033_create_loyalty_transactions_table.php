<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['earned', 'spent', 'expired', 'adjusted', 'bonus', 'referral']);
            $table->integer('points');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('description')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
