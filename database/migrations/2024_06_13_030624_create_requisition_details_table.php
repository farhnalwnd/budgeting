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
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->id();
            $table->string('rqdNbr');
            $table->foreign('rqdNbr')->references('rqmNbr')->on('requisition_masters')->onDelete('cascade');
            $table->string('rqdPart')->nullable();
            $table->string('rqdVend')->nullable();
            $table->string('rqdReqQty')->nullable();
            $table->string('rqdUm')->nullable();
            $table->string('rqdPurCost')->nullable();
            $table->string('rqdDiscPct')->nullable();
            $table->string('rqdDueDate')->nullable();
            $table->string('rqdNeedDate')->nullable();
            $table->string('rqdExpire')->nullable();
            $table->string('rqdAcct')->nullable();
            $table->string('rqdUmConv')->nullable();
            $table->string('rqdMaxCost')->nullable();
            $table->string('rqdDomain')->nullable();
            $table->string('rqdDesc')->nullable();
            $table->string('rqdStatus')->nullable();
            $table->string('rqdCmtindx')->nullable();
            $table->string('lineCmmts')->nullable();
            $table->text('rqdCmt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_details');
    }
};
