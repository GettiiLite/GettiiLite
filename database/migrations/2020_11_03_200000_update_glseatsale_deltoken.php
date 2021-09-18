<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlseatsaleDeltoken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_SEAT_SALE','delToken')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->uuid('delToken')->nullable(false)->default(0)->comment('Del token');
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
        if (Schema::hasColumn('GL_SEAT_SALE','delToken')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->dropColumn('delToken');
            });
        }
    }
}
