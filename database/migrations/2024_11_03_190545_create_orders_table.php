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
            $table->integer('event_id');
            $table->datetime('event_date');
            $table->integer('ticket_adult_price');
            $table->integer('ticket_adult_quantity');
            $table->integer('ticket_kid_price');
            $table->integer('ticket_kid_quantity');
            $table->string('barcode', 120)->unique();
            $table->integer('equal_price');
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
