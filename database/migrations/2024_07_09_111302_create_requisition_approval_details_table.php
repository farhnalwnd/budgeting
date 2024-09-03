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
        Schema::create('requisition_approval_details', function (Blueprint $table) {
            $table->id();
            $table->string('rqdaNbr');
            $table->foreign('rqdaNbr')->references('rqmNbr')->on('requisition_masters')->onDelete('cascade');
            $table->string('rqdaAction')->nullable();
            $table->string('rqdaAprUserid')->nullable();
            $table->string('rqdaTime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_approval_details');
    }
};
