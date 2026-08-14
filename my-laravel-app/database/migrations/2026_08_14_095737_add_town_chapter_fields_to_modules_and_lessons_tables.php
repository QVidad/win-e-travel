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
        Schema::table('course_modules', function (Blueprint $table) {
            $table->json('quick_facts')->nullable()->after('key_spots');
            $table->json('video_references')->nullable()->after('quick_facts');
        });

        Schema::table('module_lessons', function (Blueprint $table) {
            $table->string('cover_image', 500)->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropColumn(['quick_facts', 'video_references']);
        });

        Schema::table('module_lessons', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });
    }
};
