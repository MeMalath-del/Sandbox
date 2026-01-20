<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->enum('vehicle_type', ['motorcycle', 'car', 'van', 'pickup', 'truck']);
            $table->string('make'); // Toyota, Ford, etc.
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->string('plate_number');
            $table->string('registration_number')->nullable();
            $table->string('registration_image')->nullable();
            $table->date('registration_expiry')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('insurance_image')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('inspection_image')->nullable();
            $table->date('inspection_expiry')->nullable();
            $table->json('vehicle_images')->nullable();
            $table->decimal('max_weight_capacity', 8, 2)->nullable(); // in kg
            $table->decimal('max_volume_capacity', 8, 2)->nullable(); // in cubic meters
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
