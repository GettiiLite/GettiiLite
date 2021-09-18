<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGLGSSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_GSSITE')) {
            //
            Schema::create('GL_GSSITE', function (Blueprint $table) {
               $table->increments('SID')->nullable(false)->comment('ID ;PK');
               $table->string('aid',64)->nullable(false)->comment('api利用コード');
               $table->string('xcdkey',64)->nullable(false)->comment('検知コード用のKey');
               $table->string('url_gs',512)->nullable(false)->comment('個社GS site url');
               $table->string('url_api',512)->nullable(false)->comment('個社GS api url');
               $table->timestamps();
               $table->unique('aid');
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
        Schema::dropIfExists('GL_GSSITE');
    }
}
