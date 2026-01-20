<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('trade_name')->nullable();
            $table->string('commercial_register_number')->nullable();
            $table->string('commercial_register_image')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_certificate_image')->nullable();
            $table->string('activity_type')->nullable();
            $table->enum('company_size', ['micro', 'small', 'medium', 'large'])->nullable();
            $table->year('establishment_year')->nullable();
            $table->string('headquarters_address')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->enum('payment_terms', ['immediate', 'net_7', 'net_15', 'net_30', 'net_60'])->default('immediate');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_iban')->nullable();
            $table->text('trade_reference_1')->nullable();
            $table->text('trade_reference_2')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
