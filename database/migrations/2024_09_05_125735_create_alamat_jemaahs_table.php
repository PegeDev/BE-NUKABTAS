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
        Schema::create('alamat_jemaahs', function (Blueprint $table) {
            $table->id();
            $table->string("provinsi")->nullable();
            $table->foreign("provinsi")->references("code")->on("indonesia_provinces")->nullOnDelete();
            $table->string("kota")->nullable();
            $table->foreign("kota")->references("code")->on("indonesia_cities")->nullOnDelete();
            $table->string("kecamatan")->nullable();
            $table->foreign("kecamatan")->references("code")->on("indonesia_districts")->nullOnDelete();
            $table->string("desa")->nullable();
            $table->foreign("desa")->references("code")->on("indonesia_villages")->nullOnDelete();
            $table->unsignedBigInteger('jemaah_id');
            $table->foreign('jemaah_id')->references('id')->on('jemaahs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_jemaahs');
    }
};
