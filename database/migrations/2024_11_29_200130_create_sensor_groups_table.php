<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 50)->unique();
            $table->string('group_value', 50)->unique();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_groups');
    }
};
