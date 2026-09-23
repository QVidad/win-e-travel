<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certificate_settings', function (Blueprint $table) {
            $table->id();
            $table->string('university_name')->default('Mariano Marcos State University');
            $table->string('college_name')->default('College of Business, Economics and Accountancy');
            $table->string('signer_name')->default('Prof. Maria Santos');
            $table->string('signer_title')->default('CBEA Program Director');
            $table->string('signature_image_path')->nullable();
            $table->string('university_logo_path')->nullable();
            $table->string('college_logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('background_image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_settings');
    }
};
