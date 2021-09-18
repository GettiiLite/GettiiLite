<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCancelOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_CANCEL_ORDER', function (Blueprint $table) {
            $table->unique('order_id');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_CANCEL_ORDER', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
        });  
    }
}
