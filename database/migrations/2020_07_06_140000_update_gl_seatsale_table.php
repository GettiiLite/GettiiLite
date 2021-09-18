<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlSeatsaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_SEAT_SALE','sale_type')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->tinyInteger('sale_type')->nullable(false)->default('0')->comment('Type of sale')->after('schedule_id');
            });
        }
        if (!Schema::hasColumn('GL_SEAT_SALE','reserve_code')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->unsignedInteger('reserve_code')->nullable(true)->comment('押えコード;FK')->after('seat_class_id');
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
        if (Schema::hasColumn('GL_SEAT_SALE','sale_type')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->dropColumn('sale_type');
            });
        }
        if (Schema::hasColumn('GL_SEAT_SALE','reserve_code')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->dropColumn('reserve_code');
            });
        }
    }
}
