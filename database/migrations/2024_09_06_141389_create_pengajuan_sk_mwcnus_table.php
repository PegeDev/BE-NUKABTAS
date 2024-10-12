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
        Schema::create('pengajuan_sk_mwcnus', function (Blueprint $table) {
            $table->id();
            $table->string("surat_permohonan");
            $table->string("ba_konferensi");
            $table->string("ba_rapat_formatur");
            $table->string("daftar_riwayat_hidup");
            $table->string("kta")->nullable();
            $table->string("sertifikat_kaderisasi")->nullable();
            $table->string("kesediaan");
            $table->string("ktp");
            $table->string("surat_keputusan")->nullable();
            $table->json("dok_ba_konferensi");
            $table->json("dok_ba_rapat_formatur");
            $table->string("status")->default("pending");
            $table->unsignedBigInteger('mwcnu_id');
            $table->foreign('mwcnu_id')->references('id')->on('mwcnus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sk_mwcnus');
    }
};
