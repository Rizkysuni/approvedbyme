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
        Schema::create('nilai_sempro', function (Blueprint $table) {
            
                $table->id();
                $table->unsignedBigInteger('id_sempro');
                $table->unsignedBigInteger('id_dosen');
                $table->integer('nilai_1');
                $table->integer('nilai_2');
                $table->integer('nilai_3');
                $table->integer('nilai_4');
                $table->integer('nilai_5');
                $table->timestamps();
    
                // Definisikan foreign key constraint
                $table->foreign('id_sempro')->references('id')->on('sempros')->onDelete('cascade');
                $table->foreign('id_dosen')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sempro');
    }
};
