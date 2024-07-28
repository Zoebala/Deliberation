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
        Schema::create('elementcoupons', function (Blueprint $table) {
            $table->id();
            $table->integer("tj")->nullable();
            $table->integer("examenS1")->nullable();
            $table->integer("examenS2")->nullable();
            $table->unsignedBigInteger("cours_id");
            $table->unsignedBigInteger("coupon_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elementcoupons');
    }
};
