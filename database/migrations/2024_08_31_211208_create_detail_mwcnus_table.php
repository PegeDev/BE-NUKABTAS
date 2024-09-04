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
        Schema::create('detail_mwcnus', function (Blueprint $table) {
            $table->id();
            $table->string("nama_ketua")->nullable();
            $table->string("telp_ketua")->nullable();
            $table->string("email")->nullable();
            $table->string("alamat")->nullable();
            $table->string("google_maps")->nullable();
            $table->string("nama_admin")->nullable();
            $table->string("telp_admin")->nullable();
            $table->string("alamat_admin")->nullable();
            $table->string("surat_tugas")->nullable();
            $table->unsignedBigInteger("mwcnu_id")->nullable();
            $table->foreign("mwcnu_id")->references("id")->on("mwcnus")->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_mwcnus');
    }
};
