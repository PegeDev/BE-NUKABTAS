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
        Schema::create('form_mwcnus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("mwcnu_id");
            $table->foreign("mwcnu_id")->references("id")->on("mwcnus")->onDelete("cascade");
            $table->string("code");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_mwcnus');
    }
};
