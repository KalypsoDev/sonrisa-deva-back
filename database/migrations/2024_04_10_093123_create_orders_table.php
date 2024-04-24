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
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->integer('total_quantity')->nullable();
            $table->double('total_price', 8, 2)->nullable();
            $table->enum('status',['Preparing','Shipped', 'Cancelled'])->default('Preparing');
            $table->date('requested_date');
            $table->date('shipping_date')->nullable();
            $table->date('cancelled_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
