<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMbpsInputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_MOBAPASS_INPUT')) {
            //
            Schema::create('GL_MOBAPASS_INPUT', function (Blueprint $table) {
               $table->increments('ID')->nullable(false)->comment('ID ;PK');
               $table->tinyInteger('data_kbn')->nullable(false)->default(1)->comment('データ区分');
               $table->string('data_id',50)->nullable(false)->comment('データID');
               $table->timestamp('input_date')->nullable(false)->useCurrent()->comment('入力日時');
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
        Schema::dropIfExists('GL_MOBAPASS_INPUT');
    }
}
