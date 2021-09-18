<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class createGlUserex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_USER_EX')) {
            Schema::create('GL_USER_EX', function (Blueprint $table) {
                $table->unsignedInteger('GLID')->nullable(false)->comment('GLID');
                $table->string('parameter',100)->nullable(false)->comment('パラメーター');
                $table->text('value')->nullable()->comment('バリュー');
                $table->timestamps();
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
        Schema::dropIfExists('GL_USER_EX');
    }
}
