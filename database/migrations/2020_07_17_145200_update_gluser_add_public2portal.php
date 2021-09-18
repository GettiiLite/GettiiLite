<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGluserAddPublic2portal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_USER','public2portal')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->tinyInteger('public2portal')->nullable(false)->default('1')->comment('公開活動至portal site')->after('SID');
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
        if (Schema::hasColumn('GL_USER','public2portal')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->dropColumn('public2portal');
            });
        }
    }
}
