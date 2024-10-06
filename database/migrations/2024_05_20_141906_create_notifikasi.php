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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->enum('status', ['TERBACA', 'BELUM TERBACA']);
            $table->foreignId('perangkat_id')
                ->constrained('perangkat')
                ->cascadeOnDelete();
            $table->enum('color', [
                'primary',
                'secondary',
                'danger',
                'success',
                'warning',
                'info'
            ])->default('primary');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
