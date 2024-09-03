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
        Schema::connection('mysql_pcr')->create('pcr_revisions', function (Blueprint $table) {
            $table->id();
            $table->string('no');
            $table->integer('revision');
            $table->date('date_revision');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_pcr')->dropIfExists('pcr_revisions');
    }
};
