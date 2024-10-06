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
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis',['PILIHAN','TAMBAHAN']);
            $table->foreignId('cf_id')->nullable()->constrained('cf_pengguna')->cascadeOnDelete();
            $table->foreignId('diagnosa_id')->constrained('diagnosa')->cascadeOnDelete();
            $table->foreignId('gejala_id')->constrained('gejala')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};