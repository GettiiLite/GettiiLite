<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGltranid01 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_TRANID')) {
            Schema::table('GL_TRANID', function (Blueprint $table) {
                $table->string('tranid',80)->nullable(false)->comment('取引ID;PK')->change();
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
        if (Schema::hasTable('GL_TRANID')) {
            Schema::table('GL_TRANID', function (Blueprint $table) {
                $table->string('tranid',64)->nullable(false)->comment('取引ID;PK')->change();
            });
        }
    }
}
