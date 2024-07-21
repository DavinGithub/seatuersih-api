<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_create_reviews_table.php
// Contoh migrasi menggunakan Laravel migration
public function up()
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->enum('order_type', ['regular_clean', 'deep_clean']);
        $table->text('review');
        $table->decimal('rating', 3, 1);
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('order_id');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
