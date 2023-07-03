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
            $table->integer('nilai01')->nullable()->change();
            $table->integer('nilai02')->nullable()->change();
            $table->integer('nilai03')->nullable()->change();
            $table->integer('nilai04')->nullable()->change();
            $table->integer('nilai05')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sempros', function (Blueprint $table) {
            //
        });
    }
};
