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
        Schema::table('sempros', function (Blueprint $table) {
            $table->integer('dospem1')->change();
            $table->integer('dospem2')->change();
            $table->integer('penguji1')->change();
            $table->integer('penguji2')->change();
            $table->integer('penguji3')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sempros', function (Blueprint $table) {
            $table->string('dospem1')->change();
            $table->string('dospem2')->change();
            $table->string('penguji1')->change();
            $table->string('penguji2')->change();
            $table->string('penguji3')->change();
        });
    }
};
