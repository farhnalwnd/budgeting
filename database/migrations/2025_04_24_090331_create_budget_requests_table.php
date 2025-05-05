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
        Schema::create('budget_requests', function (Blueprint $table) {
            $table->id();
            $table->string('budget_req_no');
            $table->foreignId('from_department_id')->constrained(
                table:'departments',
                indexName:'fk_fromDepartment_departments'
            ); //pemohon
            $table->foreignId('to_department_id')->constrained(
                table:'departments',
                indexName:'fk_toDepartment_departments'
            ); //pemberi
            $table->foreignId('budget_purchase_no')
                ->nullable()    
                ->constrained(
                table:'purchases',
                indexName:'fk_budgetPurchases_purchases'
            ); //no purchasing
            $table->decimal('amount',18,2); // jumlah diminta
            $table->text('reason'); // alasan permintaan
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('feedback')->nullable(); // alasan penolakan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_requests');
    }
};
