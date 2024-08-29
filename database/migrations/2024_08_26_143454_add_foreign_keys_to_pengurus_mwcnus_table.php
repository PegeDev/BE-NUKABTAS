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
            $table->foreign(['jemaah_id'])->references(['id'])->on('jemaahs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kecamatan_id'])->references(['id'])->on('kecamatans')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengurus_mwcnus', function (Blueprint $table) {
            $table->dropForeign('pengurus_mwcnus_jemaah_id_foreign');
            $table->dropForeign('pengurus_mwcnus_kecamatan_id_foreign');
        });
    }
};
