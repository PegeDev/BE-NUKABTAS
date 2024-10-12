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
        Schema::create('lembaga_mwcnus', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan')->nullable();
            $table->unsignedBigInteger('jemaah_id');
            $table->foreign("jemaah_id")->references("id")->on("jemaahs")->onDelete("cascade");
            $table->unsignedBigInteger('surat_keputusan_mwcnu_id');
            $table->foreign('surat_keputusan_mwcnu_id')->references('id')->on('surat_keputusan_mwcnus')->onDelete('cascade');
            $table->unsignedBigInteger('lembaga_id');
            $table->foreign("lembaga_id")->references("id")->on("lembaga_masters")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_mwcnus');
    }
};
