<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaleTypeGlPerformance extends Migration
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
                $table->tinyInteger('sale_type')->nullable(false)->default(1)->comment('活動販賣狀態')->after('trans_flg');
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
            $table->dropColumn('sale_type');
        });
    }
}
