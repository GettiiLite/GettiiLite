<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class glsalesterm0712 extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_SALES_TERM')) {
            Schema::table('GL_SALES_TERM', function (Blueprint $table) {
                $table->dropUnique('term_index_unique');
                $table->unique(['performance_id','member_kbn','treat_kbn','sales_kbn'],'term_index_unique');

                $table->dateTime('reserve_st_date')->nullable(true)->comment('予約開始日時')->change();
                $table->dateTime('reserve_cl_date')->nullable(true)->comment('予約締切日時')->change();
                $table->string('sales_kbn_nm',20)->nullable(true)->default('')->comment('販売区分名称')->change();
                
                DB::statement("ALTER TABLE GL_SALES_TERM
                CHANGE COLUMN member_kbn member_kbn TINYINT NOT NULL DEFAULT 0 COMMENT '会員区分;unique' ,
                CHANGE COLUMN treat_kbn treat_kbn TINYINT NOT NULL DEFAULT 3 COMMENT '取扱区分;unique',
                CHANGE COLUMN treat_flg treat_flg TINYINT NOT NULL DEFAULT 0 COMMENT '取扱フラグ',
                CHANGE COLUMN sales_kbn sales_kbn TINYINT NOT NULL DEFAULT 1 COMMENT '販売区分;unique',
                CHANGE COLUMN reserve_st_kbn reserve_st_kbn TINYINT NOT NULL DEFAULT 2 COMMENT '予約開始区分',
                CHANGE COLUMN reserve_cl_kbn reserve_cl_kbn TINYINT NOT NULL DEFAULT 2 COMMENT '予約開始区分';
                ");
                // $table->tinyInteger('member_kbn')->nullable(false)->default('0')->comment('会員区分;unique')->change();
                // $table->tinyInteger('treat_kbn')->nullable(false)->default('3')->comment('取扱区分;unique')->change();
                // $table->tinyInteger('treat_flg')->nullable(false)->default('0')->comment('取扱フラグ')->change();
                // $table->tinyInteger('sales_kbn')->nullable(false)->default('1')->comment('販売区分;unique')->change();
                // $table->tinyInteger('reserve_st_kbn')->nullable(false)->default('2')->comment('予約開始区分')->change();
                // $table->tinyInteger('reserve_cl_kbn')->nullable(false)->default('2')->comment('予約締切区分')->change();
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
        if (Schema::hasTable('GL_SALES_TERM')) {
            Schema::table('GL_SALES_TERM', function (Blueprint $table) {
                $table->dropUnique('term_index_unique');
                $table->unique(['performance_id','member_kbn','treat_flg','sales_kbn'],'term_index_unique');

                // $table->dateTime('reserve_st_date')->nullable(false)->comment('予約開始日時')->change();
                // $table->dateTime('reserve_cl_date')->nullable(false)->comment('予約締切日時')->change();
                $table->string('sales_kbn_nm',20)->nullable(false)->comment('販売区分名称')->change();


                DB::statement("ALTER TABLE GL_SALES_TERM
                CHANGE COLUMN member_kbn member_kbn TINYINT NULL DEFAULT 0 COMMENT '会員区分;unique' ,
                CHANGE COLUMN treat_kbn treat_kbn TINYINT NULL COMMENT '取扱区分;unique',
                CHANGE COLUMN treat_flg treat_flg TINYINT NULL DEFAULT 0 COMMENT '取扱フラグunique',
                CHANGE COLUMN sales_kbn sales_kbn TINYINT NULL DEFAULT 1 COMMENT '販売区分;unique',
                CHANGE COLUMN reserve_st_kbn reserve_st_kbn TINYINT NULL DEFAULT 2 COMMENT '予約開始区分',
                CHANGE COLUMN reserve_cl_kbn reserve_cl_kbn TINYINT NULL DEFAULT 2 COMMENT '予約開始区分';
                ");

                // $table->tinyInteger('member_kbn')->nullable(true)->default(0)->comment('会員区分;unique')->change();
                // $table->tinyInteger('treat_kbn')->nullable(true)->comment('取扱区分;unique')->change();
                // $table->tinyInteger('treat_flg')->nullable(true)->default('0')->comment('取扱フラグ;unique')->change();
                // $table->tinyInteger('sales_kbn')->nullable(true)->default('1')->comment('販売区分;unique')->change();
                // $table->tinyInteger('reserve_st_kbn')->nullable(true)->default('2')->comment('予約開始区分')->change();
                // $table->tinyInteger('reserve_cl_kbn')->nullable(true)->default('2')->comment('予約締切区分')->change();
            });

        }
    }
}