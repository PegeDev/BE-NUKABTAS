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
        Schema::create('detail_jemaahs', function (Blueprint $table) {
            $table->id();
            $table->string("penghasilan");
            $table->string("pekerjaan");
            $table->string("pendidikan_terakhir");
            $table->json("riwayat_pendidikan")->nullable();
            $table->json("riwayat_pesantren")->nullable();
            $table->string("foto_ktp")->nullable();
            $table->string("alamat_detail");
            $table->json("riwayat_organisasi")->nullable();
            $table->json("riwayat_organisasi_external")->nullable();
            $table->json("riwayat_kaderisasi")->nullable();
            $table->string("sertifikat_saada")->nullable();
            $table->unsignedBigInteger('jemaah_id');
            $table->foreign("jemaah_id")->references("id")->on("jemaahs")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jemaah');
    }
};
