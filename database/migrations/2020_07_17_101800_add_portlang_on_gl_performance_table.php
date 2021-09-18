<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPortlangOnGlPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_PERFORMANCE')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->tinyInteger('autotranslation')->nullable(false)->default('0')->comment('自動翻訳 0:希望しない 1:希望する');
                $table->tinyInteger('portlanguage')->nullable(false)->default('0')->comment('ポータル言語 0:なし 1:英語 2:中国語 3:英語と中国語 4:全て');
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
        Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
            $table->dropColumn('autotranslation');
            $table->dropColumn('portlanguage');
          });
    }
}
