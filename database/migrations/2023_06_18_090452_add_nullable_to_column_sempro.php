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
            $table->unsignedBigInteger('id_dosen')->nullable()->change();
            $table->string('nilai01')->nullable()->change();
            $table->string('nilai02')->nullable()->change();
            $table->string('nilai03')->nullable()->change();
            $table->string('nilai04')->nullable()->change();
            $table->string('nilai05')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sempros', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->nullable(false)->change();
            $table->string('nilai01')->change();
            $table->string('nilai02')->change();
            $table->string('nilai03')->change();
            $table->string('nilai04')->change();
            $table->string('nilai05')->change();
        });
    }
};
