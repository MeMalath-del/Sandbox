<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_one_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_two_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['user_store', 'user_driver', 'store_driver', 'support']);
            $table->string('subject')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->foreignId('last_message_by')->nullable()->constrained('users');
            $table->boolean('user_one_archived')->default(false);
            $table->boolean('user_two_archived')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_one_id', 'user_two_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
