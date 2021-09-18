<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class createGLTicketinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_TICKETING_INFO')) {
            Schema::create('GL_TICKETING_INFO', function (Blueprint $table) {
                $table->increments('ID')->comment('ID;PK');
                $table->unsignedInteger('order_id')->nullable(false)->comment('予約_ID;FK');
                $table->unsignedInteger('seat_sale_id')->nullable()->comment('座席販売id;FK');
                $table->tinyInteger('pickup_method')->nullable(false)->comment('引取方法');
                $table->string('TicketID',255)->nullable(false)->comment('發券時識別用ID');
                $table->tinyInteger('status')->nullable(false)->default(0)->comment('發券狀態');
                $table->string('parameter01',255)->nullable()->comment('票券資訊自訂參數1（用於檢索）');
                $table->string('parameter02',255)->nullable()->comment('票券資訊自訂參數2（用於檢索）');
                $table->text('parameter03')->nullable()->comment('票券資訊自訂參數3（用於檢索）');
                $table->text('parameter04')->nullable()->comment('票券資訊自訂參數4（用於檢索）');
                $table->longText('detailInfo')->nullable(false)->comment('票券資訊詳細內容(JSON format)');
                $table->text('notes')->nullable()->comment('備註');
                $table->string('logName',36)->nullable(false)->comment('translog file name');
                $table->timestamps();

                $table->foreign('order_id')->references('order_id')->on('GL_GENERAL_RESERVATION');
                $table->foreign('seat_sale_id')->references('seat_sale_id')->on('GL_SEAT_SALE');
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
        Schema::dropIfExists('GL_TICKETING_INFO');
    }
}
