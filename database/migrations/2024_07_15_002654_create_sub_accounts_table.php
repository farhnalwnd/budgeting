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
        Schema::create('sub_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('sb_sub')->unique();
            $table->string('sb_desc');
            $table->string('sb_active');
            $table->timestamps();
        });

        Schema::table('requisition_masters', function (Blueprint $table) {
            $table->string('rqmSub')->after('rqmRmks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_accounts');
    }
};
