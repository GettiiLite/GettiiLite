<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketLayout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL_TICKET_LAYOUT', function (Blueprint $table) {
            $table->text('free_word')->nullable(false)->comment('自由表示欄')->change();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GL_TICKET_LAYOUT', function (Blueprint $table) {
            $table->string('free_word',200)->nullable(false)->comment('自由表示欄')->change();
          });
    }
}
