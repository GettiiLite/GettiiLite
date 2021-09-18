<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendSidToUserOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('GL_USER', 'SID')) {
            //
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->comment('GSSITE.SID;FK');
                $table->foreign('SID')->references('SID')->on('GL_GSSITE');
            });    
        }
        if (!Schema::hasColumn('GL_GENERAL_RESERVATION', 'SID')) {
            //
            Schema::table('GL_GENERAL_RESERVATION', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->comment('GSSITE.SID;FK');
                $table->foreign('SID')->references('SID')->on('GL_GSSITE');
            });    
        }
        if (!Schema::hasColumn('GL_SEAT_SALE', 'SID')) {
            //
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->comment('GSSITE.SID;FK');
                $table->foreign('SID')->references('SID')->on('GL_GSSITE');
            });    
        }
        if (!Schema::hasColumn('GL_GMO_TRANS', 'SID')) {
            //
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->comment('GSSITE.SID;FK');
                $table->foreign('SID')->references('SID')->on('GL_GSSITE');
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
        if (Schema::hasColumn('GL_USER', 'SID')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->dropForeign('gl_user_sid_foreign');
                $table->dropColumn('SID');
            });
        }
        if (Schema::hasColumn('GL_GENERAL_RESERVATION', 'SID')) {
            Schema::table('GL_GENERAL_RESERVATION', function (Blueprint $table) {
                $table->dropForeign('gl_general_reservation_sid_foreign');
                $table->dropColumn('SID');
            });
        }
        if (Schema::hasColumn('GL_SEAT_SALE', 'SID')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->dropForeign('gl_seat_sale_sid_foreign');
                $table->dropColumn('SID');
            });
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'SID')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                $table->dropForeign('gl_gmo_trans_sid_foreign');
                $table->dropColumn('SID');
            });
        }

    }
}
