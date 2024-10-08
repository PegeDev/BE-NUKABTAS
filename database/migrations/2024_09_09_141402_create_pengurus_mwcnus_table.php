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
            $table->id();
            $table->string("jabatan");
            $table->string('posisi')->nullable();
            $table->json('masa_khidmat');
            $table->unsignedBigInteger('jemaah_id');
            $table->foreign("jemaah_id")->references("id")->on("jemaahs")->onDelete("cascade");
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
        Schema::dropIfExists('pengurus_mwcnus');
    }
};
