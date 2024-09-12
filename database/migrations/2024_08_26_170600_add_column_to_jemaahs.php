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
        Schema::table('jemaahs', function (Blueprint $table) {
            $table->string("status_pernikahan")->nullable();
            $table->string("kepengurusan")->nullable();
            $table->string("jabatan_kepengurusan")->nullable();
            $table->string("pekerjaan")->nullable();
            $table->string('penghasilan')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jemaahs', function (Blueprint $table) {
            $table->dropIfExists("status_pernikahan");
        });
    }
};
