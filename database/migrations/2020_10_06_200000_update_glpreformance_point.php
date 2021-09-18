<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlpreformancePoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_PERFORMANCE','point_kbn')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->tinyInteger('point_kbn')->nullable(false)->default(1)->comment('ポイント付与タイプ設定');
            });
        }
        if (!Schema::hasColumn('GL_PERFORMANCE','point_exr')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->unsignedDecimal('point_exr', 6, 2)->nullable(false)->default(1.0)->comment('ポイント付与設定');
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
        if (Schema::hasColumn('GL_PERFORMANCE','point_kbn')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->dropColumn('point_kbn');
            });
        }
        if (Schema::hasColumn('GL_PERFORMANCE','point_exr')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->dropColumn('point_exr');
            });
        }
    }
}
