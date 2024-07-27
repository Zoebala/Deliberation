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
        Schema::create('membrejuries', function (Blueprint $table) {
            $table->id();
            $table->string("nom",50);
            $table->string("postnom",50);
            $table->string("prenom",25);
            $table->string("tel",10);
            $table->string("email",50)->nullable();
            $table->string("fonction",50);
            $table->unsignedBigInteger("jury_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membrejuries');
    }
};
