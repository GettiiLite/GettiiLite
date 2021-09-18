<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArticleGlPerformance extends Migration
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
                $table->text('article')->nullable(true)->comment('記事情報')->after('context');
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
          $table->dropColumn('article');
        });
    }
}
