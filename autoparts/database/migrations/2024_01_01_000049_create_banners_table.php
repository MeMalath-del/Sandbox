<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('title_en')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('image');
            $table->string('image_mobile')->nullable();
            $table->string('link_url')->nullable();
            $table->enum('link_type', ['url', 'product', 'category', 'store', 'promotion'])->nullable();
            $table->unsignedBigInteger('link_id')->nullable();
            $table->enum('position', ['home_slider', 'home_banner', 'category_banner', 'sidebar', 'popup'])->default('home_slider');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('clicks_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
