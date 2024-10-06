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
        Schema::create('sop', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('tugas');
            $table->string('catatan', 1000);
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('tanaman_id')->nullable()->constrained('tanaman')->onDelete('cascade');
            $table->foreignId('greenhouse_id')->constrained('greenhouse')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sop');
    }
};