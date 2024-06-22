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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('pt_part')->unique();
            $table->string('pt_rev')->nullable();
            $table->string('pt_desc1')->nullable();
            $table->string('pt_desc2')->nullable();
            $table->string('pt_abc')->nullable();
            $table->string('pt_drwg_loc')->nullable();
            $table->string('pt_status')->nullable();
            $table->string('pt_routing')->nullable();
            $table->string('pt_bom_code')->nullable();
            $table->string('pt_run')->nullable();
            $table->string('pt_um')->nullable();
            $table->string('pt_taxable')->nullable();
            $table->string('pt_net_wt')->nullable();
            $table->string('pt_net_wt_um')->nullable();
            $table->string('pt_size')->nullable();
            $table->string('pt_size_um')->nullable();
            $table->string('pt_dsgn_grp')->nullable();
            $table->string('pt_prod_line')->nullable();
            $table->string('pt_shelflife')->nullable();
            $table->string('pt_part_type')->nullable();
            $table->string('pt_group')->nullable();
            $table->string('pt_draw')->nullable();
            $table->string('pt_added')->nullable();
            $table->string('pt_buyer')->nullable();
            $table->string('pt_promo')->nullable();
            $table->string('pt_userid')->nullable();
            $table->string('pt_mod_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
