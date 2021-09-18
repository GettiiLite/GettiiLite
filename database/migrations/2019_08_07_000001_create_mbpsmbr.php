<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMbpsmbr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_MBPS_MBR')) {
            Schema::create('GL_MBPS_MBR', function (Blueprint $table) {
               $table->unsignedInteger('site_code')->nullable(false)->default(1)->comment('site code, GETTIIS is 1');
               $table->string('member_id',100)->nullable(false)->comment('会員ＩＤ');
               $table->string('app_id',20)->nullable(false)->comment('アプリ番号');
               $table->tinyInteger('del_flg')->nullable(false)->default(0)->comment('削除フラグ');
               $table->unsignedInteger('update_account_cd')->nullable(true)->comment('更新担当者コード;FK');
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
        Schema::dropIfExists('GL_MBPS_MBR');
    }
}
