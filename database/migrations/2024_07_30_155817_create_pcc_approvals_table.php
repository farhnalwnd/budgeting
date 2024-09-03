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
        Schema::connection('mysql_pcr')->create('pcc_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pcr_id')->constrained('pcrs')->cascadeOnDelete();
            $table->foreignId('pcc_id')->constrained('pccs')->cascadeOnDelete();
            $table->string('approval_status');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_pcr')->dropIfExists('pcc_approvals');
    }
};
