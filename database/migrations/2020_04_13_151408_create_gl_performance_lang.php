<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlPerformanceLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_PERFORMANCE_LANG', function (Blueprint $table) {
            $table->increments('lang_id')->nullable(false)->comment('語言ID;PK');
            $table->longText('lang_info')->nullable(false)->comment('語言內容;JSON');
            $table->string('lang_code')->nullable(false)->comment('語言代碼;ISO 639-1');
            $table->unsignedInteger('performance_id')->nullable(false)->comment('公演ID');
            $table->timestamps();

            $table->foreign('performance_id')->references('performance_id')->on('GL_PERFORMANCE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_PERFORMANCE_LANG');
    }
}
