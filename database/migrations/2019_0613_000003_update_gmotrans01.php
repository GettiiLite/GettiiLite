<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class gmotrans01 extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_GMO_TRANS', 'order_id')) {
            //
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->unsignedInteger('order_id')->nullable()->comment('予約_ID;FK')->change();
            });    
        }

        if (Schema::hasTable('GL_GMO_TRANS')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->string('req_result',10)->nullable(true)->comment('resule code of request');
                $table->string('req_errCode',20)->nullable(true)->comment('error code of request');
                $table->string('req_errMsg',200)->nullable(true)->comment('error msg of request');
                $table->string('redirect_url',512)->nullable(true)->comment('redirect url of request');
                $table->string('checksum',512)->nullable(false)->default("")->comment('');
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
        // if (Schema::hasColumn('GL_GMO_TRANS', 'order_id')) {
        //     //
        //     Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
        //         $table->unsignedInteger('order_id')->nullable(false)->default(0)->comment('予約_ID;FK')->change();
        //     });    
        // }
        if (Schema::hasColumn('GL_GMO_TRANS', 'req_result')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropColumn('req_result');
            });
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'req_errCode')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropColumn('req_errCode');
            });
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'req_errMsg')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropColumn('req_errMsg');
            });
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'redirect_url')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropColumn('redirect_url');
            });
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'checksum')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropColumn('checksum');
            });
        }

    }
}