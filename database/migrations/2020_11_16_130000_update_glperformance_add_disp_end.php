<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlperformanceAddDispEnd extends Migration
{     
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_PERFORMANCE','disp_end')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                 $table->dateTime('disp_end')->nullable(true)->after('disp_start')->comment('表示終了日時');
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
        if (Schema::hasColumn('GL_PERFORMANCE','disp_end')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->dropColumn('disp_end');
            });
        }
    }
}
