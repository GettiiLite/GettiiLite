<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlAmountRevise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_AMOUNT_REVISE','revise_info')) {
            Schema::table('GL_AMOUNT_REVISE', function (Blueprint $table) {
                $table->text('revise_info')->nullable(false)->default(null)->comment('修改資訊')->after('amount_total');
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
        if (Schema::hasColumn('GL_AMOUNT_REVISE','revise_info')) {
            Schema::table('GL_AMOUNT_REVISE', function (Blueprint $table) {
                $table->dropColumn('revise_info');
            });
        }
    }
}
