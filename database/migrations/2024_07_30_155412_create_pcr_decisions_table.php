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
        Schema::connection('mysql_pcr')->create('pcr_decisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pcr_id');
            $table->foreign('pcr_id')->references('id')->on('pcrs')->onDelete('cascade');
            $table->string('decision_status');
            $table->date('date_decision');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_pcr')->dropIfExists('pcr_decisions');
    }
};
