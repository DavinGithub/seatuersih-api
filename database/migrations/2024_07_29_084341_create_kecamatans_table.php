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
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('kecamatan');
            $table->unsignedBigInteger('laundry_id');
            $table->timestamps();

            $table->foreign('laundry_id')->references('id')->on('laundries')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropForeign(['laundry_id']);
        });
        Schema::dropIfExists('kecamatans');
    }
};
