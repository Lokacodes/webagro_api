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
        Schema::create('kontrol_lingkungan', function (Blueprint $table) {
            $table->id();
            $table->string('catatan', 1000);
            $table->date('tanggal');
            $table->foreignId('pupuk_id')->constrained('pupuk')->onDelete('cascade');
            $table->foreignId('tanaman_id')->constrained('tanaman')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrol_lingkungan');
    }
};