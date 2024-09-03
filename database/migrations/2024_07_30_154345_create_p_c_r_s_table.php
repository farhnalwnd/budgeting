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
        Schema::connection('mysql_pcr')->create('pcrs', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg');
            $table->longText('description_of_change');
            $table->longText('reason_of_change');
            $table->text('estimated_benefit');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_pcr')->dropIfExists('p_c_r_s');
    }
};
