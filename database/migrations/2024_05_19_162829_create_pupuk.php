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
        Schema::create('pupuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kandungan');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->foreignId('jenis_id')->constrained('jenis')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('cascade');
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
        Schema::dropIfExists('pupuk');
    }
};