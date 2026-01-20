<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('balance')->default(0);
            $table->integer('total_earned')->default(0);
            $table->integer('total_spent')->default(0);
            $table->integer('total_expired')->default(0);
            $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum', 'vip'])->default('bronze');
            $table->timestamps();
            
            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
