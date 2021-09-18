<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameValidperiodToApplydateOnGlcommissionclient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_COMMISSION_CLIENT', function (Blueprint $table) {
            $table->dateTime('valid_period')->comment('摘要日')->change();
            $table->renameColumn('valid_period', 'apply_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_COMMISSION_CLIENT', function (Blueprint $table) {
            $table->dateTime('apply_date')->comment('有効期限')->change();
            $table->renameColumn('apply_date', 'valid_period');
        });    
    }
}
