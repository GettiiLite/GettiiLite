<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class tov10spec extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_SCHEDULE', 'refund_st_date')) {
            //
            Schema::table('GL_SCHEDULE', function (Blueprint $table) {
                $table->dateTime('refund_st_date')->nullable(true)->after('cancel_flg')->comment('払戻開始日;2019/5/20 Added');
            });    
        }

        if (!Schema::hasColumn('GL_SCHEDULE', 'refund_end_date')) {
            Schema::table('GL_SCHEDULE', function (Blueprint $table) {
                $table->dateTime('refund_end_date')->nullable(true)->after('refund_st_date')->comment('払戻終了日;2019/5/20 Added');
            });
        }
        if (!Schema::hasColumn('GL_COMMISSION', 'paynpick_id')) {
            Schema::table('GL_COMMISSION', function (Blueprint $table) {
                $table->unsignedInteger('paynpick_id')->nullable(true)->after('performance_id')->comment('FK,unique;Ref. to GL_PAY_PICK.paynpick_id');
                $table->foreign('paynpick_id')->references('paynpick_id')->on('GL_PAY_PICK');
                $table->index('GLID');
                $table->dropUnique(['GLID','performance_id','comission_kbn']);
                $table->unique(['GLID','performance_id','paynpick_id','comission_kbn'],'commission_unique' );
            });
        }
        if (Schema::hasColumn('GL_PAY_PICK', 'commission_code')) {
            Schema::table('GL_PAY_PICK', function (Blueprint $table) {
                $table->dropForeign(['commission_code']);
                $table->dropColumn('commission_code');
            });
        }
        Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
            $table->unsignedInteger('order_id')->nullable(true)->comment('予約_ID;FK,Unique')->change();
            $table->string('ticket_class_name',80)->nullable(true)->comment('券種名')->change();;
        });

        if (Schema::hasColumn('GL_SALES_TERM', 'reserve_period_code')) {
            Schema::table('GL_SALES_TERM', function (Blueprint $table) {
                $table->index('performance_id');
                $table->dropUnique('term_index_unique');
                $table->dropColumn('reserve_period_code');
                $table->unique(['performance_id','member_kbn','treat_flg','sales_kbn'],'term_index_unique');
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
        if (!Schema::hasColumn('GL_SALES_TERM', 'reserve_period_code')) {
            Schema::table('GL_SALES_TERM', function (Blueprint $table) {
                $table->dropUnique('term_index_unique');
                $table->tinyInteger('reserve_period_code')->nullable(true)->comment('予約期間コード;unique');
                $table->unique(['performance_id','reserve_period_code','member_kbn','treat_flg','sales_kbn'],'term_index_unique');
                $table->dropIndex(['performance_id']);

            });
        }

        // Cant't rollback
        // Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
        //     // $table->string('ticket_class_name',80)->nullable(false)->default('')->comment('券種名')->change();;
        //     // $table->unsignedInteger('order_id')->nullable(false)->comment('予約_ID;FK,Unique')->change();
        // });

        if (!Schema::hasColumn('GL_PAY_PICK', 'commission_code')) {
            Schema::table('GL_PAY_PICK', function (Blueprint $table) {
                Schema::disableForeignKeyConstraints();
                $table->unsignedInteger('commission_code')->nullable(true)->comment('手数料コード;FK');
                $table->foreign('commission_code')->references('commission_code')->on('GL_COMMISSION');
                Schema::enableForeignKeyConstraints();
            });
        }
        if (Schema::hasColumn('GL_COMMISSION', 'paynpick_id')) {
            Schema::table('GL_COMMISSION', function (Blueprint $table) {
                $table->dropUnique('commission_unique');
                $table->unique(['GLID','performance_id','comission_kbn']);                
                $table->dropIndex(['GLID']);
                $table->dropForeign(['paynpick_id']);
                $table->dropColumn('paynpick_id');
            });
        }
        if (Schema::hasColumn('GL_SCHEDULE', 'refund_st_date')) {
            Schema::table('GL_SCHEDULE', function (Blueprint $table) {
                $table->dropColumn('refund_st_date');
            });    
        }
        if (Schema::hasColumn('GL_SCHEDULE', 'refund_end_date')) {
            Schema::table('GL_SCHEDULE', function (Blueprint $table) {
                $table->dropColumn('refund_end_date');
            });    
        }
    }
}