<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sensor_id')->constrained('sensors')->onDelete('cascade');
            $table->boolean('email_notification_allowed')->default(0);
            $table->double('threshold', 5, 2)->nullable();
            $table->integer('cooldown')->nullable();
            $table->double('min_unit_diff', 4, 2)->nullable();
            $table->timestamp('last_notification_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
