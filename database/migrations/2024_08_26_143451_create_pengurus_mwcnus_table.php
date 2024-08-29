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
        Schema::create('pengurus_mwcnus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('jabatan');
            $table->unsignedBigInteger('kecamatan_id')->index('pengurus_mwcnus_kecamatan_id_foreign');
            $table->unsignedBigInteger('jemaah_id')->index('pengurus_mwcnus_jemaah_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengurus_mwcnus');
    }
};
