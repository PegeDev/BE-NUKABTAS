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
        Schema::table('pengurus_mwcnus', function (Blueprint $table) {
            $table->dropForeign('pengurus_mwcnus_kecamatan_id_foreign');
            $table->dropColumn('kecamatan_id');

            $table->unsignedBigInteger('mwcnu_id');
            $table->foreign('mwcnu_id')->references('id')->on('mwcnus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengurus_mwcnus', function (Blueprint $table) {
            //
        });
    }
};
