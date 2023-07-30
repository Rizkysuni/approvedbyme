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
            $table->string('status_nilai')->default('belum dinilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sempros', function (Blueprint $table) {
            $table->dropColumn('status_nilai');
        });
    }
};
