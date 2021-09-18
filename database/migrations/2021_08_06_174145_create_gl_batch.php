<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_BATCH', function (Blueprint $table) {
            $table->increments('ID')->comment('inc');
            $table->tinyInteger('system_kbn')->default(0)->comment('1:GETTIIS');
            $table->tinyInteger('process_kbn')->default(0)->comment('システム区分：GETTIIS 1: 会員情報取込');
            $table->timestamp('exec_time');
            $table->tinyInteger('status')->default(0)->comment('1:正常終了');

            $table->unique(['system_kbn', 'process_kbn']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_BATCH');
    }
}
