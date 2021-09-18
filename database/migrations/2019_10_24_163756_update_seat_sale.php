<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSeatSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
            $table->unsignedInteger('ticket_class_id')->nullable()->comment('券種id;FK')->change();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 不可逆
        // Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
        //     $table->unsignedInteger('ticket_class_id')->nullable(false)->comment('券種id;FK')->change();
        //   });
    }
}
