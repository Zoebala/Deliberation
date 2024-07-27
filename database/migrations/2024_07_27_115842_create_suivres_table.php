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
        Schema::create('suivres', function (Blueprint $table) {
            $table->id();
            $table->date("periodeD");
            $table->date("periodeF");
            $table->integer("tj");
            $table->integer("examen");
            $table->unsignedBigInteger("etudiant_id");
            $table->unsignedBigInteger("cours_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivres');
    }
};
