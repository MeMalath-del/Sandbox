<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('working_hours')->nullable();
            $table->json('holidays')->nullable();
            $table->boolean('is_open')->default(true);
            $table->enum('store_type', ['individual', 'company', 'agency', 'distributor'])->default('individual');
            $table->json('specializations')->nullable(); // e.g., ['engine', 'brakes', 'electrical']
            $table->json('brands')->nullable(); // brands they sell
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->text('return_policy')->nullable();
            $table->text('warranty_policy')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->json('payment_methods')->nullable();
            $table->json('shipping_methods')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'active', 'suspended', 'closed'])->default('pending');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
