<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class accountunique01 extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_ACCOUNT')) {
            //
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                $table->unique(['GLID', 'account_code']);
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
            //
            Schema::table('GL_ACCOUNT', function (Blueprint $table) {
                $table->dropUnique(['GLID', 'account_code']);
            });    
        }
    }
}