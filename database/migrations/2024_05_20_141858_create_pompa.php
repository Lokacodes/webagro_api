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
        Schema::create('pompa', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['MATI', 'HIDUP']);
            $table->enum('auto', ['MATI', 'HIDUP'])
                ->default('MATI');
            $table->string('keterangan');
            $table->foreignId('perangkat_id')->constrained('perangkat')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pompa');
    }
};
