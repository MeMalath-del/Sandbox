<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_make_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('car_model_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('car_year_id')->nullable()->constrained()->onDelete('set null');
            $table->string('custom_make')->nullable();
            $table->string('custom_model')->nullable();
            $table->year('year')->nullable();
            $table->string('vin')->nullable(); // Vehicle Identification Number
            $table->string('plate_number')->nullable();
            $table->string('color')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('transmission')->nullable();
            $table->integer('mileage')->nullable();
            $table->date('purchase_date')->nullable();
            $table->json('images')->nullable();
            $table->string('nickname')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_cars');
    }
};
