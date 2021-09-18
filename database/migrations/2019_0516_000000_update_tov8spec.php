<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class tov8spec extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_TRANID')) {
            Schema::create('GL_TRANID', function (Blueprint $table) {
                $table->string('tranid',64)->nullable(false)->comment('取引ID;PK');
                $table->timestamp('expired_time')->nullable(false)->comment('有効期限');
                $table->tinyInteger('statecd')->nullable(false)->default(1)->comment('処理状態');
                $table->tinyInteger('req_format')->nullable(false)->default(1)->comment('要求形式');
                $table->text('req_data')->nullable(false)->comment('要求データ');
                $table->timestamps();
    
                $table->primary('tranid');
            });    
        }

        if (!Schema::hasTable('GL_SN')) {
            Schema::create('GL_SN', function (Blueprint $table) {
                $table->unsignedInteger('GLID')->nullable(false)->comment('GLID;PK,FK');
                $table->string('yearnmonth',6)->nullable(false)->comment('年月;PK');
                $table->unsignedMediumInteger('tentative_order_sn')->nullable(false)->default(0)->comment('仮予約番号');
                $table->unsignedMediumInteger('order_sn')->nullable(false)->default(0)->comment('予約番号');
                $table->unsignedMediumInteger('ticketing_sn')->nullable(false)->default(0)->comment('発券番号');
                $table->unsignedMediumInteger('receipt_sn')->nullable(false)->default(0)->comment('入金番号');
                $table->unsignedMediumInteger('lottery_sn')->nullable(false)->default(0)->comment('抽選予約番号');
                $table->unsignedMediumInteger('refund_sn')->nullable(false)->default(0)->comment('払戻番号');
                $table->timestamps();
    
                $table->primary(['GLID','yearnmonth']);
                $table->foreign('GLID')->references('GLID')->on('GL_USER');
            });    
        }

        if (!Schema::hasTable('GL_GMO_TRANS')) {
            Schema::create('GL_GMO_TRANS', function (Blueprint $table) {
                $table->increments('ID')->nullable(false)->comment('ID;PK');
                $table->unsignedInteger('order_id')->nullable(false)->comment('予約_ID;FK');
                $table->uuid('order_number')->nullable(false)->comment('予約Code;Unique');
                $table->unsignedInteger('trans_code')->nullable(true)->comment('トランザクションコード');
                $table->string('user_id',20)->nullable(true)->comment('ユーザーID(for GMO)');
                $table->unsignedInteger('payment_code')->nullable(true)->comment('決済方法');
                $table->unsignedSmallInteger('state')->nullable(true)->comment('ステータス');
                $table->dateTime('charge_date')->nullable(true)->comment('課金日時');
                $table->timestamps();
    
                $table->foreign('order_id')->references('order_id')->on('GL_GENERAL_RESERVATION');
                $table->unique('order_number');
            });
        }
        if (!Schema::hasColumn('GL_NONRESERVED_STOCK', 'current_num')) {
            //
            Schema::table('GL_NONRESERVED_STOCK', function (Blueprint $table) {
                $table->unsignedInteger('current_num')->nullable(false)->default(0)->after('stock_limit')->comment('整理券採番');
            });    
        }

        if (!Schema::hasColumn('GL_SEAT_SALE', 'temp_reserve_sn')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->string('temp_reserve_sn',20)->nullable(true)->after('ticket_class_name_short')->comment('仮予約番号');
            });
        }
        if (!Schema::hasColumn('GL_SEAT_SALE', 'seat_class_id')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->unsignedInteger('seat_class_id')->nullable(true)->after('schedule_id')->comment('FK;Ref. to GL_SEAT_CLASS.seat_class_id');
                $table->foreign('seat_class_id')->references('seat_class_id')->on('GL_SEAT_CLASS');    
            });
        }

        Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
            $table->unsignedInteger('seat_seq')->nullable(false)->comment('座席連番;自由席の場合は整理券採番番号')->change();
            $table->datetime('temp_reserve_date')->nullable(true)->default(null)->comment('仮予約日時')->change();
            $table->unsignedInteger('temp_receive_account_cd')->nullable(true)->comment('仮予約担当者コード;FK')->change();
            $table->unsignedInteger('alloc_seat_id')->nullable(true)->comment('座席id;FK')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
            $table->datetime('temp_reserve_date')->nullable(false)->comment('仮予約日時')->change();
            $table->unsignedInteger('temp_receive_account_cd')->nullable(false)->comment('仮予約担当者コード;FK')->change();
            $table->unsignedInteger('alloc_seat_id')->nullable(false)->comment('座席id;FK')->change();
            $table->smallInteger('seat_seq')->nullable(false)->comment('座席連番')->change();
            $table->dropColumn('temp_reserve_sn');
            $table->dropForeign('gl_seat_sale_seat_class_id_foreign');
            $table->dropColumn('seat_class_id');
        });
        Schema::table('GL_NONRESERVED_STOCK', function (Blueprint $table) {
            $table->dropColumn('current_num');
        });

        Schema::dropIfExists('GL_GMO_TRANS');
        Schema::dropIfExists('GL_SN');
        Schema::dropIfExists('GL_TRANID');
    }
}