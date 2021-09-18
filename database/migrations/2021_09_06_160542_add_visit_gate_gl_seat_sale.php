<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVisitGateGlSeatSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_SEAT_SALE', 'visit_gate')) 
        {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->string('visit_gate', 20)->nullable(true)->comment('れすQアプリの「端末名」');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('GL_SEAT_SALE', 'visit_gate')) 
        {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->dropColumn('visit_gate');
            });
        }
    }
}
