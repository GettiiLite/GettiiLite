<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeywordsGlPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_PERFORMANCE')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->string('keywords',500)->nullable(true)->comment('keywords')->after('article');
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
        Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
          $table->dropColumn('keywords');
        });
    }
}
