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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->integer('phone');
            $table->integer('total_price');
            $table->datetime('pickup_date');
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shoes_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shoes_id')->references('id')->on('shoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'shoes_id']);
        });
        Schema::dropIfExists('orders');
    }
};

