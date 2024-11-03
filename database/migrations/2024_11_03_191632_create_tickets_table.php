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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('ticket_type', ['adult', 'kid', 'discount', 'group']);
            $table->integer('ticket_price');
            $table->integer('ticket_quantity');
            $table->string('barcode', 120)->unique();
            $table->timestamps(); // Qo'shimcha: timestamp larni qo'shish
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};