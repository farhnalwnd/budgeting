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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('vd_addr')->nullable()->unique();
            $table->string('vd_taxable')->default(false);
            $table->string('vd_sort')->nullable();
            $table->string('ad_name')->nullable();
            $table->string('ad_line1')->nullable();
            $table->string('ad_line2')->nullable();
            $table->string('ad_line3')->nullable();
            $table->string('ad_city')->nullable();
            $table->string('vd_type')->nullable();
            $table->string('vd_cr_term')->nullable();
            $table->string('ad_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
