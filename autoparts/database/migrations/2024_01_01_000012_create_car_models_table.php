<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_make_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug');
            $table->year('year_from')->nullable();
            $table->year('year_to')->nullable();
            $table->string('body_type')->nullable(); // sedan, suv, truck, etc.
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['car_make_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
