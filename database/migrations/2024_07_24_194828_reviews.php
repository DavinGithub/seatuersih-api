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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type', ['Regular Clean','Deep Clean']);
            $table->text('review');
            $table->decimal('rating', 3, 1);
            $table->date('review_date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('laundry_id'); // Changed to laundry_id
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('laundry_id')->references('id')->on('laundries')->onDelete('cascade'); // Added foreign key to laundries table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['laundry_id']);
        });
        Schema::dropIfExists('reviews');
    }
};
