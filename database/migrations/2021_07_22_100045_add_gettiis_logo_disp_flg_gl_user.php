<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGettiisLogoDispFlgGlUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_USER')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->tinyInteger('GETTIIS_logo_disp_flg')->nullable(true)->default('0')->comment('GETTIISの販売元一覧に、ロゴ画像を載せるかのフラグ 0:不掲載 1:掲載');
                
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
          Schema::table('GL_USER', function (Blueprint $table) {
            $table->dropColumn('GETTIIS_logo_disp_flg');
        });
    }
}
