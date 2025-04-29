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
            $table->string('budget_no')->unique();
            // $table->foreignId('department_id')->constrained(
            //     table:'departments',
            //     indexName:'fk_purchases_department'
            // );
            $table->string('item_name');
            $table->integer('quanitity');
            $table->string('um')->nullable(); // unit measure
            $table->decimal('amount',18,2); //budget awal / estimasi
            $table->decimal('total_amount',18,2); //total budget awal / total estimasi
            // $table->decimal('actual_amount',18,2); //biaya aktual pembelian
            // $table->integer('PO'); //nomor po
            $table->text('remarks')->nullable(); //keterangan
            // $table->foreignId('category_id')->constrained(
            //     table:'category_masters',
            //     indexName:'fk_purchases_categoryMasters'
            // )->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
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
