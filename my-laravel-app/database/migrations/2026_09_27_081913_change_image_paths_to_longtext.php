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
        Schema::table('certificate_settings', function (Blueprint $table) {
            $table->longText('university_logo_path')->nullable()->change();
            $table->longText('college_logo_path')->nullable()->change();
            $table->longText('signature_image_path')->nullable()->change();
            $table->longText('background_image_path')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->longText('avatar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_settings', function (Blueprint $table) {
            $table->string('university_logo_path')->nullable()->change();
            $table->string('college_logo_path')->nullable()->change();
            $table->string('signature_image_path')->nullable()->change();
            $table->string('background_image_path')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->change();
        });
    }
};
