<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class createGLPayinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_PAY_INFO')) {
            Schema::create('GL_PAY_INFO', function (Blueprint $table) {
                $table->increments('ID')->comment('ID;PK');
                $table->unsignedInteger('order_id')->nullable(false)->comment('予約_ID;FK');
                $table->tinyInteger('pay_method')->nullable(false)->comment('支払方法');
                $table->string('transID',255)->nullable(false)->comment('付款時識別用ID');
                $table->tinyInteger('status')->nullable(false)->default(0)->comment('付款交易狀態');
                $table->string('parameter01',255)->nullable()->comment('付款資訊自訂參數1（用於檢索）');
                $table->string('parameter02',255)->nullable()->comment('付款資訊自訂參數2（用於檢索）');
                $table->string('parameter03',255)->nullable()->comment('付款資訊自訂參數3（用於檢索）');
                $table->string('parameter04',255)->nullable()->comment('付款資訊自訂參數4（用於檢索）');
                $table->longText('detailInfo')->nullable(false)->comment('付款資訊詳細內容(JSON format)');
                $table->text('notes')->nullable()->comment('備註');
                $table->string('logName',36)->nullable(false)->comment('translog file name');
                $table->timestamps();

                $table->foreign('order_id')->references('order_id')->on('GL_GENERAL_RESERVATION');
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
        Schema::dropIfExists('GL_PAY_INFO');
    }
}
