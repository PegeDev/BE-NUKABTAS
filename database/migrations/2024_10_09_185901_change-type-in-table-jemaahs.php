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
            $table->dropColumn('kecamatan_id');

            $table->string('profile_picture')->nullable()->change();
            $table->string('penghasilan')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('nama_panggilan')->nullable()->change();
            $table->string('pekerjaan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jemaahs', function (Blueprint $table) {
            //
        });
    }
};
