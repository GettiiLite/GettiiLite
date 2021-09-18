<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlQuestion extends Migration
{     
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_QUESTION','question_title')) {
            Schema::table('GL_QUESTION', function (Blueprint $table) {
                $table->dropColumn('question_title');
            });
        }
        if (Schema::hasColumn('GL_QUESTION','question_text')) {
            Schema::table('GL_QUESTION', function (Blueprint $table) {
                $table->dropColumn('question_text');
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
        if (!Schema::hasColumn('GL_QUESTION','question_title')) {
            Schema::table('GL_QUESTION', function (Blueprint $table) {
                $table->text('question_title')->nullable()->after('use_flg')->comment('タイトル');
            });
        }
        if (!Schema::hasColumn('GL_QUESTION','question_text')) {
            Schema::table('GL_QUESTION', function (Blueprint $table) {
                $table->text('question_text')->nullable(false)->after('question_title')->comment('質問文');
            });
        }
    }
}
