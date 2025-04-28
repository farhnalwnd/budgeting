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
            $table->string('budget_allocation_no')->unique();
            $table->foreignId('department_id')->constrained(
                table:'departments',
                indexName:'fk_budgetAllocation_departments'
                )->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->decimal('total_amount',8,2)->nullable();
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
