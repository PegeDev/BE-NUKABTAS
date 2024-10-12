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
        Schema::create('jemaah_surat_keputusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jemaah_id')->constrained()->onDelete('cascade');
            $table->foreignId('surat_keputusan_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemaah_surat_keputusan');
    }
};
