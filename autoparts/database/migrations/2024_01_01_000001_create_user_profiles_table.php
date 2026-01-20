<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify users table to add user type and additional fields
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['buyer_individual', 'buyer_company', 'driver_individual', 'driver_company', 'store_individual', 'store_company', 'admin'])->default('buyer_individual')->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('phone_secondary')->nullable()->after('phone');
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('avatar')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_image_front')->nullable();
            $table->string('id_image_back')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'banned'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->boolean('email_verified')->default(false);
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->string('preferred_language')->default('ar');
            $table->string('preferred_currency')->default('SAR');
            $table->string('timezone')->default('Asia/Riyadh');
            $table->boolean('dark_mode')->default(false);
            $table->boolean('notifications_email')->default(true);
            $table->boolean('notifications_sms')->default(true);
            $table->boolean('notifications_push')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type', 'phone', 'phone_secondary', 'username', 'avatar',
                'birth_date', 'gender', 'nationality', 'id_number',
                'id_image_front', 'id_image_back', 'status', 'is_verified',
                'phone_verified', 'email_verified', 'two_factor_enabled',
                'two_factor_secret', 'two_factor_recovery_codes',
                'preferred_language', 'preferred_currency', 'timezone',
                'dark_mode', 'notifications_email', 'notifications_sms',
                'notifications_push', 'last_login_at', 'last_login_ip', 'deleted_at'
            ]);
        });
    }
};
