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
        Schema::create('surat_keputusan_mwcnus', function (Blueprint $table) {
            $table->id();
            $table->string("nomor_surat");
            $table->string("start_khidmat");
            $table->string("end_khidmat");
            $table->string("file_surat");
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
        Schema::dropIfExists('surat_keputusan_mwcnus');
    }
};
