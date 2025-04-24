<?php

use App\Models\User;
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
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_list-id')->constrained(
                table:'budget_lists', 
                indexName:'fk_budgetAllocation_budgetList'
                )->cascadeOnDelete();
            $table->foreignId('department_id')->constrained(
                table:'departments',
                indexName:'fk_budgetAllocation_departments'
                )->cascadeOnDelete();
            $table->foreignId('category_id')->constrained(
                table:'category_masters',
                indexName:'fk_budgetAllocation_categoryMasters'
            )->cascadeOnDelete();
            $table->text('description');
            $table->decimal('jumlah_anggaran',8,2);
            $table->string('allocated_by');
            $table->foreign('allocated_by')->references('nik')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};
