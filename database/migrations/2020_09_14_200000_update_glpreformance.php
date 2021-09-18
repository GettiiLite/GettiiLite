<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlpreformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_PERFORMANCE','purchasable_number')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->integer('purchasable_number')->nullable(false)->default(0)->comment('購入可能累計枚数')->change();
            });
        }
        else {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->integer('purchasable_number')->nullable(false)->default(0)->comment('購入可能累計枚数');
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
        if (Schema::hasColumn('GL_PERFORMANCE','purchasable_number')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->smallInteger('purchasable_number')->nullable(false)->default(0)->comment('購入可能累計枚数')->change();
            });
        }
        else {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->smallInteger('purchasable_number')->nullable(false)->default(0)->comment('購入可能累計枚数');
            });
        }
    }
}
