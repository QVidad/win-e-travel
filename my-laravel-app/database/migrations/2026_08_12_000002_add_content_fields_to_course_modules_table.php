<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->text('key_spots')->nullable()->after('description');
            $table->string('cover_image')->nullable()->after('key_spots');
        });
    }

    public function down(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'key_spots', 'cover_image']);
        });
    }
};
