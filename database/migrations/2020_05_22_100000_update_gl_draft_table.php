<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_DRAFT','new_status')) {
            Schema::table('GL_DRAFT', function (Blueprint $table) {
                $table->tinyInteger('new_status')->nullable(false)->default('0')->comment('Status of this draft')->after('performance_id');
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
        if (Schema::hasColumn('GL_DRAFT','new_status')) {
            Schema::table('GL_DRAFT', function (Blueprint $table) {
                $table->dropColumn('new_status');
            });
        }
    }
}
