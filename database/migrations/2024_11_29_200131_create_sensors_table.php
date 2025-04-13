<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_group_id')->constrained('sensor_groups')->onDelete('cascade');
            $table->string('sensor_name', 50)->nullable();
            $table->string('type', 50)->unique();
            $table->string('display_name', 50)->unique();
            $table->string('unit_of_measurement', 10)->nullable();
            $table->boolean('is_output_binary')->default(false);
            $table->string('color_class', 50)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->string('icon_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
