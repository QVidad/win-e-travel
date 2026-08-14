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
            $table->string('type')->default('town')->after('id'); // 'town' or 'final'
            $table->foreignId('town_id')->nullable()->constrained()->onDelete('cascade')->after('type');
        });

        Schema::create('simulation_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('simulation_id')->constrained()->onDelete('cascade');
            $table->boolean('passed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_user');
        
        Schema::table('simulations', function (Blueprint $table) {
            $table->dropForeign(['town_id']);
            $table->dropColumn(['type', 'town_id']);
        });
    }
};
