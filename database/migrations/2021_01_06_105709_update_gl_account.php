<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_ACCOUNT','pw_renew_date')) {
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                 $table->dateTime('pw_renew_date')->nullable(true)->default(null)->after('password')->comment('密碼更新日期');
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
        if (Schema::hasColumn('GL_ACCOUNT','pw_renew_date')) {
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                $table->dropColumn('pw_renew_date');
            });
        }
    }
}
