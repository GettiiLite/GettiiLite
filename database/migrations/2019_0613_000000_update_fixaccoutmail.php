<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class fixaccoutmail extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_ACCOUNT', 'mail_address')) {
            //
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                $table->string('mail_address',200)->nullable()->default(null)->comment('メールアドレス')->change();
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
        if (Schema::hasColumn('GL_ACCOUNT', 'mail_address')) {
            //
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                $table->string('mail_address',200)->nullable(false)->comment('メールアドレス')->change();
            });    
        }
    }
}