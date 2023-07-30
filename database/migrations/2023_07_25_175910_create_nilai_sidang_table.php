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
        Schema::create('nilai_sidang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sempro');
            $table->unsignedBigInteger('id_dosen');
            $table->integer('nilai_1');
            $table->integer('nilai_2');
            $table->integer('nilai_3');
            $table->integer('nilai_4');
            $table->integer('nilai_5');
            $table->integer('nilai_6');
            $table->integer('nilai_7');
            $table->integer('nilai_8');
            $table->integer('nilai_9');
            // tambahkan atribut lain sesuai kebutuhan

            // Definisikan foreign key
            $table->foreign('id_sempro')->references('id')->on('sempros')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sidang');
    }
};
