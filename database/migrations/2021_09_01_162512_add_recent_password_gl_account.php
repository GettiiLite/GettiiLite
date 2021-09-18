<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecentpasswordGlAccount extends Migration
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
                $table->text('recent_password')->nullable(true)->comment('最近のパスワード');
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
                $table->dropColumn('recent_password');
            });
        }
    }
}
