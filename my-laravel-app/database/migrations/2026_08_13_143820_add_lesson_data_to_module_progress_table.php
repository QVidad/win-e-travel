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
        Schema::table('module_progress', function (Blueprint $table) {
            $table->json('lesson_data')->nullable()->after('unlocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_progress', function (Blueprint $table) {
            $table->dropColumn('lesson_data');
        });
    }
};
