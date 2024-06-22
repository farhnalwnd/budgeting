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
        Schema::create('approvers', function (Blueprint $table) {
            $table->id();
            $table->string('oid_rqa_mstr')->nullable()->unique();
            $table->string('rqa_cc_from')->nullable();
            $table->string('rqa_cc_to')->nullable();
            $table->string('rqa_apr')->nullable();
            $table->string('rqa_apr_level')->nullable();
            $table->string('rqa_apr_req')->nullable();
            $table->string('rqa_start')->nullable();
            $table->string('rqa_end')->nullable();
            $table->string('approver')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvers');
    }
};
