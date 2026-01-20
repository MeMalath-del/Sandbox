<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label')->default('home'); // home, work, other
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('country')->default('Saudi Arabia');
            $table->string('region')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->string('building_number')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('landmark')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
