<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_CANCEL_ORDER')) {
            Schema::create('GL_CANCEL_ORDER', function (Blueprint $table) {
               $table->increments('cancel_order_id')->nullable(false)->comment('ID ;取消_訂單_ID');
               $table->unsignedInteger('order_id')->nullable(false)->references('order_id')->on('GL_GENERAL_RESERVATION')->comment('予約_ID');
               $table->tinyInteger('status')->nullable(false)->default(1)->comment('退款狀態');
               $table->string('status_message', 100)->nullable(false)->default("")->comment('退款狀態信息');
               $table->tinyInteger('refund_kbn')->nullable(false)->default(1)->comment('退款區分');
               $table->string('refund_inf',511)->nullable(false)->comment('退款資料');
               $table->unsignedDecimal('refund_payment', 6, 2)->nullable(false)->default(0)->comment('退款金額');
               $table->unsignedInteger('update_account_cd')->nullable(false)->references('account_cd')->on('GL_ACCOUNT')->comment('更新担当者コード');
               $table->timestamp('refund_due_date')->nullable(true)->comment('退款完成日');
               $table->timestamps();
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
        Schema::dropIfExists('GL_CANCEL_ORDER');
    }
}
