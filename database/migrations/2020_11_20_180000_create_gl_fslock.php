<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class createGLFslock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_FSLOCK')) {
            Schema::create('GL_FSLOCK', function (Blueprint $table) {
                $table->unsignedInteger('schedule_id')->nullable(false)->comment('スケジュールID;PK');
                $table->unsignedInteger('seat_class_id')->nullable(false)->comment('席種id;FK');

                $table->primary(['schedule_id','seat_class_id']);
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
        Schema::dropIfExists('GL_FSLOCK');
    }
}
