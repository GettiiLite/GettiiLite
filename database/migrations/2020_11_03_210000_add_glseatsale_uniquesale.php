<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlseatsaleUniquesale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
            $table->unique(['alloc_seat_id','schedule_id','seat_class_id','seat_seq','delToken'],'unique_sale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
            $table->dropForeign(['alloc_seat_id']);
            $table->dropUnique('unique_sale');
            $table->foreign('alloc_seat_id')->references('alloc_seat_id')->on('GL_SEAT');
        });
    }
}
