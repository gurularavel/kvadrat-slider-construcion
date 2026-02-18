<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Default Slider');
            $table->unsignedInteger('width')->default(1200);
            $table->unsignedInteger('height')->default(500);
            $table->string('background_color', 50)->default('rgb(255,255,255)');
            $table->boolean('show_arrows')->default(true);
            $table->boolean('show_pages')->default(true);
            $table->boolean('auto_play')->default(false);
            $table->boolean('stop_auto_play_mouse_over')->default(true);
            $table->boolean('effects_from_first')->default(false);
            $table->json('slides_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
