<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGLQuestionAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_QUESTION_ANSWER')) {
            Schema::create('GL_QUESTION_ANSWER', function (Blueprint $table) {
                $table->increments('answer_id')->comment('回答ID;PK');
                // $table->increments('question_id')->comment('質問ID;PK');
                $table->unsignedInteger('question_id')->nullable(false)->comment('質問ID;FK');
                $table->unsignedInteger('order_id')->nullable(false)->comment('予約ID;FK');
                $table->string('answer_text',2000)->nullable(false)->comment('回答文');
                $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
                $table->timestamps();

                $table->foreign('question_id')->references('question_id')->on('GL_QUESTION');
                $table->foreign('order_id')->references('order_id')->on('GL_GENERAL_RESERVATION');
                $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
                $table->unique(['question_id', 'order_id']);
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
        Schema::dropIfExists('GL_QUESTION_ANSWER');
    }
}
