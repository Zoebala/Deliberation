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
        Schema::create('recours', function (Blueprint $table) {
            $table->id();
            $table->string("motif");
            $table->json("contenu");
            $table->unsignedBigInteger("annee_id");
            $table->unsignedBigInteger("cours_id");
            $table->unsignedBigInteger("etudiant_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recours');
    }
};
