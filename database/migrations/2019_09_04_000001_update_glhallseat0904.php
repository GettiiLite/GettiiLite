<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updateglhallseat0904 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_HALL_SEAT')) {
            Schema::table('GL_HALL_SEAT', function (Blueprint $table) {
                $table->unsignedInteger('prio_seat')->nullable(true)->default('0')->comment('優先順位座席')->change();
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
        if (Schema::hasTable('GL_HALL_SEAT')) {
            Schema::table('GL_HALL_SEAT', function (Blueprint $table) {
                $table->smallInteger('prio_seat')->nullable(true)->default('0')->comment('優先順位座席')->change();
            });
        }
    }
}
