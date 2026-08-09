<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('town_id')->constrained('towns')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('cultural'); // cultural, historical, natural, ecotourism
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('history')->nullable();
            $table->text('significance')->nullable();
            $table->string('coordinates')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
