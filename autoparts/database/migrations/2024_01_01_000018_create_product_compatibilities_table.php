<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_make_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('car_model_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('car_year_id')->nullable()->constrained()->onDelete('cascade');
            $table->year('year_from')->nullable();
            $table->year('year_to')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('engine_code')->nullable();
            $table->string('transmission')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'car_make_id', 'car_model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_compatibilities');
    }
};
