<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGeneralReservation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_GENERAL_RESERVATION', function (Blueprint $table) {
          $table->tinyInteger('mobapass_trans_flg')->nullable(false)->default(0)->after('commission_uc')->comment('モバパス連携フラグ');
          $table->tinyInteger('mobapass_cancel_flg')->nullable(false)->default(0)->after('mobapass_trans_flg')->comment('モバパスキャンセルフラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_GENERAL_RESERVATION', function (Blueprint $table) {
          $table->dropColumn('mobapass_trans_flg');
          $table->dropColumn('mobapass_cancel_flg');
        });
    }
}
