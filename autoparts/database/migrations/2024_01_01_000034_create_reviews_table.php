<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            
            // Reviewable (Product, Store, or Driver)
            $table->morphs('reviewable');
            
            // Ratings
            $table->tinyInteger('rating'); // 1-5
            $table->tinyInteger('quality_rating')->nullable();
            $table->tinyInteger('value_rating')->nullable();
            $table->tinyInteger('delivery_rating')->nullable();
            
            // Review Content
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->boolean('would_recommend')->default(true);
            
            // Media
            $table->json('images')->nullable();
            $table->string('video_url')->nullable();
            
            // Verification
            $table->boolean('is_verified_purchase')->default(false);
            
            // Moderation
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users');
            $table->timestamp('moderated_at')->nullable();
            
            // Interactions
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->integer('report_count')->default(0);
            
            // Reply
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['reviewable_type', 'reviewable_id', 'status']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
