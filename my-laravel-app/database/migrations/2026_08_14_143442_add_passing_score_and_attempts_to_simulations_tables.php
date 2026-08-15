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
        Schema::table('simulations', function (Blueprint $table) {
            $table->integer('passing_score')->default(80)->after('title');
        });

        Schema::table('simulation_user', function (Blueprint $table) {
            $table->integer('attempts')->default(0)->after('passed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simulations', function (Blueprint $table) {
            $table->dropColumn('passing_score');
        });

        Schema::table('simulation_user', function (Blueprint $table) {
            $table->dropColumn('attempts');
        });
    }
};
