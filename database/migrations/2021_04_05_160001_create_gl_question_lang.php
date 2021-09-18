<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGLQuestionLang extends Migration
{     
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_QUESTION_LANG')) {
            Schema::create('GL_QUESTION_LANG', function (Blueprint $table) {
                $table->increments('lang_id')->comment('言語ID;PK');
                $table->unsignedInteger('question_id')->nullable(false)->comment('質問ID;FK');
                $table->string('lang_code', 255)->nullable(false)->comment('言語コード');
                $table->text('question_title')->nullable(true)->comment('タイトル');
                $table->text('question_text')->nullable(false)->comment('質問文');
                $table->text('answer_placeholder')->nullable(true)->comment('回答記入例');
                $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
                $table->timestamps();

                $table->foreign('question_id')->references('question_id')->on('GL_QUESTION');
                $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
                $table->unique(['question_id', 'lang_code']);
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
        Schema::dropIfExists('GL_QUESTION_LANG');
    }
}
