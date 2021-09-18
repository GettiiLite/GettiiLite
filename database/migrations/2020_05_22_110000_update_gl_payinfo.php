<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlPayinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_PAY_INFO','parameter03')) {
            Schema::table('GL_PAY_INFO', function (Blueprint $table) {
                $table->text('parameter03')->nullable()->comment('付款資訊自訂參數3（用於檢索）')->change();
            });
        }
        if (Schema::hasColumn('GL_PAY_INFO','parameter04')) {
            Schema::table('GL_PAY_INFO', function (Blueprint $table) {
                $table->text('parameter04')->nullable()->comment('付款資訊自訂參數4（用於檢索）')->change();
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
        // if (Schema::hasColumn('GL_PAY_INFO','parameter03')) {
        //     Schema::table('GL_PAY_INFO', function (Blueprint $table) {
        //         $table->string('parameter03',255)->nullable()->comment('付款資訊自訂參數3（用於檢索）')->change();
        //     });
        // }
        // if (Schema::hasColumn('GL_PAY_INFO','parameter04')) {
        //     Schema::table('GL_PAY_INFO', function (Blueprint $table) {
        //         $table->string('parameter04',255)->nullable()->comment('付款資訊自訂參數4（用於檢索）')->change();
        //     });
        // }

    }
}
