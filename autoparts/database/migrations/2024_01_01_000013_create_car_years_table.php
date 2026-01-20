<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_model_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->string('engine_type')->nullable(); // petrol, diesel, hybrid, electric
            $table->string('engine_size')->nullable(); // 1.6L, 2.0L, etc.
            $table->string('transmission')->nullable(); // manual, automatic, cvt
            $table->string('drive_type')->nullable(); // fwd, rwd, awd, 4wd
            $table->string('engine_code')->nullable();
            $table->string('chassis_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['car_model_id', 'year', 'engine_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_years');
    }
};
