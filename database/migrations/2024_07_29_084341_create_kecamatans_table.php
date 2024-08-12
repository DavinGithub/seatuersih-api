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
            $table->unsignedBigInteger('kabupaten_id');
            $table->timestamps();

            // Perbaikan: Foreign key mengacu pada tabel 'kabupatens'
            $table->foreign('kabupaten_id')->references('id')->on('kabupatens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropForeign(['kabupaten_id']);
        });
        Schema::dropIfExists('kecamatans');
    }
};
