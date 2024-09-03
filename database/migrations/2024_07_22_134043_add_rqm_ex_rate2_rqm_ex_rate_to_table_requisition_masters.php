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
        Schema::table('requisition_masters', function (Blueprint $table) {
            $table->string('rqmEmailOpt')->nullable();
            $table->string('rqmEntDate')->nullable();
            $table->string('rqmEntEx')->nullable();
            $table->string('rqmExRate')->nullable();
            $table->string('rqmExRate2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_masters', function (Blueprint $table) {
            $table->dropColumn('rqmEmailOpt');
            $table->dropColumn('rqmEntDate');
            $table->dropColumn('rqmEntEx');
            $table->dropColumn('rqmExRate');
            $table->dropColumn('rqmExRate2');
        });
    }
};
