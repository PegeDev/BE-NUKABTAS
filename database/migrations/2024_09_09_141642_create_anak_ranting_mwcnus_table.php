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
        Schema::create('anak_ranting_mwcnus', function (Blueprint $table) {
            $table->id();
            $table->string("jabatan");
            $table->string('posisi');
            $table->string('masa_khidmat');
            $table->unsignedBigInteger('kepengurusan_id');
            $table->foreign('kepengurusan_id')->references('id')->on('kepengurusan_mwcnus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak_ranting_mwcnus');
    }
};
