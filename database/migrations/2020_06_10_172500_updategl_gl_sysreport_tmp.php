<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateglGlSysreportTmp extends Migration
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
            $table->dateTime('apply_date')->nullable(true)->default(null)->comment('摘要日, unique')->after('payment_type');
            $table->dropUnique('gl_sysreport_tmp_unique');
            $table->unique(['id','performance_id', 'commission_type', 'payment_type', 'apply_date'],'gl_sysreport_tmp_unique');
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
            $table->dropUnique('gl_sysreport_tmp_unique');
            $table->unique(['id','performance_id', 'commission_type', 'payment_type'],'gl_sysreport_tmp_unique');
            $table->dropColumn('apply_date');
        });
    }
}
