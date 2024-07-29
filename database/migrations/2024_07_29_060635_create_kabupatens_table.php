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
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->id();
            $table->string('kabupaten');
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
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['laundry_id']);
        });
        Schema::dropIfExists('kabupatens');
    }
};
