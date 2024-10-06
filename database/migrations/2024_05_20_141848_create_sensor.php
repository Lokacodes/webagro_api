<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensor', function (Blueprint $table) {
            $table->id();
            $table->decimal('sensor_suhu', 5, 2);
            $table->integer('sensor_kelembaban');
            $table->integer('sensor_ldr');
            $table->integer('sensor_tds');
            $table->integer('sensor_waterflow');
            $table->integer('sensor_volume')->default(0);
            $table->foreignId('perangkat_id')
                ->constrained('perangkat')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor');
    }
};