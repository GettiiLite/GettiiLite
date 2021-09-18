<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AmountRevise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_AMOUNT_REVISE', function (Blueprint $table) {
            $table->increments('amount_id')->nullable(false)->comment('ID;PK');
            $table->unsignedInteger('order_id')->nullable(false)->comment('予約_ID;FK,Unique');
            $table->tinyInteger('amount_status')->nullable(false)->default(0)->comment('修改状態');
            $table->decimal('amount_total')->nullable(false)->comment('修改金額總金額');
            $table->text('amount_memo')->nullable(false)->comment('修改原因');
            $table->unsignedInteger('update_account_cd')->nullable(false)->comment('更新担当者コード;FK');
            $table->timestamps();

            $table->unique(['order_id']);
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->foreign('order_id')->references('order_id')->on('GL_GENERAL_RESERVATION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_AMOUNT_REVISE');
    }
}
