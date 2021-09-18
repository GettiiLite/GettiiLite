<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateglGlSysreportTmp2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_SYSREPORT_TMP', function (Blueprint $table) {
            //
            $table->decimal('unit_rate',4,2)->nullable(false)->default(0)->comment('料率')->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_SYSREPORT_TMP', function (Blueprint $table) {
            //
            $table->dropColumn('unit_rate');
        });
    }
}
