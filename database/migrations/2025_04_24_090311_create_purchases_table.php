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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no')->unique();
            $table->foreignId('department_id')->constrained(
                table:'departments',
                indexName:'fk_purchases_department'
            );
            $table->integer('PO')->nullable();
            $table->decimal('actual_amount',18,2)->nullable(); //biaya aktual pembelian
            $table->decimal('grand_total',18,2)->nullable(); //biaya aktual pembelian
            // $table->foreignId('category_id')->constrained(
            //     table:'category_masters',
            //     indexName:'fk_purchases_categoryMasters'
            // )->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
