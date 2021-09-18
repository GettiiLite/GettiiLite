<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobapassoutput extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_MOBAPASS_OUTPUT')) {
            //
            Schema::create('GL_MOBAPASS_OUTPUT', function (Blueprint $table) {
               $table->increments('ID')->nullable(false)->comment('ID ;PK');
               $table->tinyInteger('data_kbn')->nullable(false)->default(1)->comment('データ区分');
               $table->string('data_id',40)->nullable(false)->comment('データID');
               $table->timestamp('output_date')->nullable(false)->useCurrent()->comment('出力日時');
               $table->string('file_name',50)->nullable(false)->comment('ファイル名');
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
        Schema::dropIfExists('GL_MOBAPASS_OUTPUT');
    }
}
