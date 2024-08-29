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
            // $table->dropForeign("jemaahs_kecamatan_id_foreign");
            // $table->dropColumn("kecamatan_id");

            $table->unsignedBigInteger("mwcnu_id");
            $table->foreign("mwcnu_id")->references("id")->on("mwcnus")->onDelete("cascade");
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
