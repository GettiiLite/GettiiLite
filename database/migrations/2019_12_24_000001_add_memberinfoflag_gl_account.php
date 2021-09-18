<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberInfoFlagGlAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_ACCOUNT')) {
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                $table->tinyInteger('member_info_flg')->nullable(false)->default(1)->after('sales_info_flg')->comment('会員情報管理;0:閲覧のみ 1:閲覧不可 2:編集可');
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
        if (Schema::hasTable('GL_ACCOUNT')) {
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
            $table->dropColumn('member_info_flg');
            });
        }
    }
}

