<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_MEMBER', function (Blueprint $table) {
            $table->increments('ID')->comment('inc');
            $table->tinyInteger('system_kbn')->default(0)->comment('1:GETTIIS');
            $table->string('member_id', 255);
            $table->string('tel_num', 26)->nullable(true);
            $table->string('mail_address', 200)->nullable(true);
            $table->tinyInteger('allow_email')->default(0)->comment('メール配信を希望するかのフラグ 0:希望しない 1:希望する');
            $table->tinyInteger('status')->nullable(true);
            $table->unsignedInteger('update_account_cd')->nullable(true);
            $table->timestamps();
            $table->foreign('update_account_cd')->references('account_cd')->on('GL_ACCOUNT');
            $table->unique(['system_kbn', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_MEMBER');
    }
}
