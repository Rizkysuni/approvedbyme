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
        Schema::table('nilai_sidang', function (Blueprint $table) {
            $table->integer('nilai_1')->nullable()->change();
            $table->integer('nilai_2')->nullable()->change();
            $table->integer('nilai_3')->nullable()->change();
            $table->integer('nilai_4')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->integer('nilai_1')->change();
        $table->integer('nilai_2')->change();
        $table->integer('nilai_3')->change();
        $table->integer('nilai_4')->change();
    }
};
