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
        Schema::create('riwayat_penyakit', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 4, 3)->nullable();
            $table->string('bobot')->nullable();
            $table->foreignId('penyakit_id')->constrained('penyakit')->cascadeOnDelete();
            $table->foreignId('diagnosa_id')->constrained('diagnosa')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penyakit');
    }
};