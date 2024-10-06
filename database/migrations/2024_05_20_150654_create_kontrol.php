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
        Schema::create('kontrol', function (Blueprint $table) {
            $table->id();
            $table->decimal('suhu_min', 5, 2);
            $table->decimal('suhu_max', 5, 2);
            $table->integer('tds_min');
            $table->integer('tds_max');
            $table->integer('kelembaban_min');
            $table->integer('kelembaban_max');
            $table->integer('volume_min');
            $table->integer('volume_max');
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
        Schema::dropIfExists('kontrol');
    }
};
