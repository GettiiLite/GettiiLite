<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlperformanceAddSellAnyone extends Migration
{     
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_PERFORMANCE','sell_anyone')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->tinyInteger('sell_anyone')->nullable(false)->default(0)->comment('非会員購入可否フラグ');
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
        if (Schema::hasColumn('GL_PERFORMANCE','sell_anyone')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->dropColumn('sell_anyone');
            });
        }
    }
}
