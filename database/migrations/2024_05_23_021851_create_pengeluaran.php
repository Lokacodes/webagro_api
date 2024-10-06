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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kategori');
            $table->string('produk');
            $table->string('catatan');
            $table->integer('jumlah');
            $table->integer('nominal');
            $table->foreignId('panen_id')->constrained('panen')->cascadeOnDelete();
            $table->foreignId('greenhouse_id')->constrained('greenhouse')->cascadeOnDelete();
            $table->foreignId('satuan_id')->nullable()->constrained('satuan')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};