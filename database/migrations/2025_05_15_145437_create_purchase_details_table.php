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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('purchase_no');
            $table->foreign('purchase_no')->references('purchase_no')->on('purchases')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('um')->nullable(); // unit measure
            $table->decimal('amount',18,2); //budget awal / estimasi
            $table->decimal('total_amount',18,2); //total budget awal / total estimasi
            $table->text('remarks')->nullable(); //keterangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
