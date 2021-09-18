<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GlCommissionClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_COMMISSION_CLIENT')) {
            Schema::create('GL_COMMISSION_CLIENT', function (Blueprint $table) {
                $table->increments('id')->nullable(false)->comment('id;PK');
                $table->unsignedInteger('GLID')->nullable(false)->comment('GLID ;FK,Unique');
                $table->tinyInteger('commission_type')->nullable(false)->comment('手数料区分');
                $table->dateTime('valid_period')->nullable(false)->comment('摘要日');
                $table->decimal('rate',3,2)->nullable(true)->comment('料率');
                $table->decimal('amount',6,2)->nullable(true)->comment('手数料');
                $table->tinyInteger('delete_flg')->nullable(false)->default(0)->comment('削除フラグ');
                $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
                $table->timestamps();
                $table->foreign('GLID')->references('GLID')->on('GL_USER');
                $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
                $table->unique(['GLID','commission_type','valid_period'],'gl_commission_client_unique');
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
        Schema::dropIfExists('GL_COMMISSION_CLIENT');
    }
}
