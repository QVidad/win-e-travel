<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('towns', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('region')->default('Ilocos Norte');
            $table->text('description')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('status')->default('published'); // published, draft
            $table->integer('order')->default(0);
            $table->string('difficulty_level')->default('Beginner');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('towns');
    }
};
