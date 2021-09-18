<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_PERFORMANCE')) {
            Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
                $table->tinyInteger('edit_status')->nullable(false)->default('0')->comment('活動編輯狀態');
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
        Schema::table('GL_PERFORMANCE', function (Blueprint $table) {
            $table->dropColumn('edit_status');
          });
    }
}
