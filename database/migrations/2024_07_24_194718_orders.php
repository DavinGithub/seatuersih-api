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
            $table->enum('order_type', ['regular_clean', 'deep_clean'])->default('regular_clean');
            $table->string('order_number');
            $table->string('detail_address');
            $table->string('phone');
            $table->integer('total_price')->nullable();
            $table->datetime('pickup_date');
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('laundry_id');
            $table->unsignedBigInteger('user_id'); 
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('decline_note')->nullable();
            $table->enum('order_status', [
                'pending',
                'waiting_for_payment',
                'in-progress',
                'completed',
                'decline',
                'reviewed' 
            ])->default('pending');
            $table->timestamps();

            $table->foreign('laundry_id')->references('id')->on('laundries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['laundry_id']);
            $table->dropForeign(['user_id']); 
        });
        Schema::dropIfExists('orders');
    }
};
