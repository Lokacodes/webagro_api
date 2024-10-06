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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('status', ['MASUK', 'IZIN', 'ALPHA', 'SAKIT']);
            $table->string('catatan', 1000);
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};