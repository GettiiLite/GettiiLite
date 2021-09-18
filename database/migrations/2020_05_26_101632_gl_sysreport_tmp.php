<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GlSysreportTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_SYSREPORT_TMP')) {
            Schema::create('GL_SYSREPORT_TMP', function (Blueprint $table) {
                $table->string('id',27)->comment('ID, unique');
                $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK, unique');
                $table->tinyInteger('commission_type')->nullable(false)->default(1)->comment('精算区分, unique');
                $table->tinyInteger('payment_type')->nullable(false)->default(1)->comment('金額区分, unique');
                $table->decimal('unit_price',6,2)->nullable(false)->default(0)->comment('単価');
                $table->decimal('number',10,0)->nullable(false)->default(0)->comment('件数');
                $table->decimal('sheets_number',10,0)->nullable(false)->default(0)->comment('枚数');
                $table->decimal('amount',10,2)->nullable(false)->default(0)->comment('金額');
                $table->unique(['id','performance_id', 'commission_type', 'payment_type'],'gl_sysreport_tmp_unique');
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
        Schema::dropIfExists('GL_SYSREPORT_TMP');
    }
}
