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
        Schema::create('budget_lists', function (Blueprint $table) {
            $table->id();
            $table->string('budget_allocation_no'); //unique budget no
            $table->foreign('budget_allocation_no')->references('budget_allocation_no')->on('budget_allocations');
            $table->string('name'); // nama item
            $table->foreignId('category_id')->constrained(
                table:'category_masters',
                indexName:'fk_budgetAllocation_categoryMasters'
            )->cascadeOnDelete(); // category
            $table->integer('quantity');
            $table->string('um'); // unit measure
            $table->decimal('default_amount', 18,2);
            $table->decimal('total_amount', 18,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_lists');
    }
};
