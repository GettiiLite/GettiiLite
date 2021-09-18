<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class gmotrans02 extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_GMO_TRANS', 'temp_reserve_sn')) {
            //
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->string('temp_reserve_sn',64)->nullable(false)->default('')->comment('');
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
        if (Schema::hasColumn('GL_GMO_TRANS', 'temp_reserve_sn')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropColumn('temp_reserve_sn');
            });
        }
    }
}