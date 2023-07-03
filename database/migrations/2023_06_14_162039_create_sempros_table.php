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
        Schema::create('sempros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('nama');
            $table->string('nim');
            $table->string('judul');
            $table->string('jurusan');
            $table->string('ruangan');
            $table->date('tglSempro');
            $table->string('dospem1');
            $table->string('dospem2');
            $table->string('penguji1');
            $table->string('penguji2');
            $table->string('penguji3');
            $table->timestamps();
            
            $table->foreign('id_mahasiswa')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sempros');
    }
};
