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
        Schema::create('requisition_masters', function (Blueprint $table) {
            $table->id();
            $table->string('rqmNbr')->unique();
            $table->string('rqmShip')->nullable();
            $table->string('rqmVend')->nullable();
            $table->string('rqmReqDate')->nullable();
            $table->string('rqmNeedDate')->nullable();
            $table->string('rqmDueDate')->nullable();
            $table->string('rqmClsDate')->nullable();
            $table->string('enterby')->nullable();
            $table->string('rqmRqbyUserid')->nullable();
            $table->string('rqmEndUserid')->nullable();
            $table->string('rqmReason')->nullable();
            $table->string('rqmRmks')->nullable();
            $table->string('rqmCc')->nullable();
            $table->string('rqmAprvStat')->nullable();
            $table->string('rqmSite')->nullable();
            $table->string('rqmEntity')->nullable();
            $table->string('rqmDomain')->nullable();
            $table->string('rqmCurr')->nullable();
            $table->string('rqmLang')->nullable();
            $table->string('emailOptEntry')->nullable();
            $table->decimal('rqmDiscPct', 10, 2)->default(0.00);
            $table->decimal('rqmTotal', 10, 2)->default(0.00);
            $table->string('rqmDirect')->default(false);
            $table->string('rqm__log01')->default(false);
            $table->integer('rqmStatus')->default(1);
            $table->string('routeToApr')->nullable();
            $table->string('routeToBuyer')->nullable();
            $table->string('rqmRtdtoPurch')->nullable();
            $table->string('allInfoCorrect')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_masters');
    }
};
