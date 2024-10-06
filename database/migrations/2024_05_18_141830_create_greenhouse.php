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
        Schema::create('greenhouse', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('pemilik')->nullable();
            $table->string('alamat')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('gambar')->nullable();
            $table->string('pengelola')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('telegram_id')->nullable();
            $table->foreignId('jenis_tanaman_id')->constrained('jenis_tanaman')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('greenhouse');
    }
};