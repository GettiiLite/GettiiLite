<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_USER','post_display')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->string('post_display', 8)->nullable(true)->comment('郵地區號')->after('post_code');
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
        if (Schema::hasColumn('GL_USER','post_display')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->dropColumn('post_display');
            });
        }
    }
}
