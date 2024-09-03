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
        Schema::connection('mysql_pcr')->create('pcr_nature_of_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pcr_id')->constrained('pcrs')->onDelete('cascade');
            $table->foreignId('nature_of_change_id')->constrained('nature_of_changes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_pcr')->dropIfExists('pcr_nature_of_changes');
    }
};
