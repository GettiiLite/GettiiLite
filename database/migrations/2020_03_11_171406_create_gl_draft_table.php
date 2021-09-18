<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_DRAFT', function (Blueprint $table) {
            $table->increments('draft_id')->nullable(false)->comment('草稿ID;PK');
            $table->longText('draft_info')->nullable(false)->comment('草稿內容');
            $table->longText('message')->nullable(true)->comment('草稿編輯信息');
            $table->integer('performance_id')->nullable(false)->comment('公演ID');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_DRAFT');
    }
}
