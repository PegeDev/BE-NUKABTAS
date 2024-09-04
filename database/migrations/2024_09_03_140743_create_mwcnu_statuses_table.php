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
        Schema::create('mwcnu_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('message')->nullable();
            $table->unsignedBigInteger("mwcnu_id")->nullable();
            $table->foreign('mwcnu_id')->references('id')->on('mwcnus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mwcnu_statuses');
    }
};
