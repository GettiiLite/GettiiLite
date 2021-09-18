<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class glportalmstoutput01 extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_PORTAL_MST_OUTPUT')) {
            //
            Schema::create('GL_PORTAL_MST_OUTPUT', function (Blueprint $table) {
                $table->string('sight_id',20)->nullable(false)->comment('サイトID, pk');
                $table->string('data_id',40)->nullable(false)->comment('データID, pk');
                $table->tinyInteger('data_kbn')->nullable(false)->default(1)->comment('データ区分,1:パッチデータ 2:スナップショットデータ');
                $table->timestamp('output_date')->nullable(false)->useCurrent()->comment('出力日時');
                $table->tinyInteger('corp_target')->nullable(false)->comment('連携対象;0:対象外 1:対象');
                $table->string('file_name',50)->nullable(false)->comment('ファイル名');
                
                $table->primary(['sight_id','data_id']);
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
        Schema::dropIfExists('GL_PORTAL_MST_OUTPUT');
    }
}