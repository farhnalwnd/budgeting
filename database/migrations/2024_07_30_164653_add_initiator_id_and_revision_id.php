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
        Schema::connection('mysql_pcr')->table('pcrs', function (Blueprint $table) {

            $table->foreignId('initiator_id')->constrained('initiators')->cascadeOnDelete();
            $table->foreignId('revision_id')->constrained('pcr_revisions')->cascadeOnDelete();        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_pcr')->table('pcrs', function (Blueprint $table) {
            $table->dropForeign(['initiator_id']);
            $table->dropForeign(['revision_id']);
            $table->dropColumn(['initiator_id']);
            $table->dropColumn(['revision_id']);
        });
    }
};
