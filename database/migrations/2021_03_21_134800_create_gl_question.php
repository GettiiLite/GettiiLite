<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGLQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_QUESTION')) {
            Schema::create('GL_QUESTION', function (Blueprint $table) {
                $table->increments('question_id')->comment('質問ID;PK');
                $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID;FK');
                $table->tinyInteger('use_flg')->nullable(false)->default(0)->comment('使用フラグ');
                $table->text('question_title')->nullable()->comment('タイトル');
                $table->text('question_text')->nullable(false)->comment('質問文');
                $table->tinyInteger('require_flg')->nullable(false)->default(0)->comment('回答必須フラグ');
                $table->smallInteger('disp_order')->nullable(false)->default(0)->comment('表示順');
                $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
                $table->timestamps();

                $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
                $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
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
        Schema::dropIfExists('GL_QUESTION');
    }
}
