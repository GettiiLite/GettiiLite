<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class changSidNotNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_USER','SID')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                DB::statement("UPDATE GL_USER
                                SET sid = 1
                                WHERE sid is null");
                $table->unsignedInteger('SID')->nullable(false)->default(1)->comment('GSSITE.SID;FK')->change();
            });
        }
        if (Schema::hasColumn('GL_GENERAL_RESERVATION', 'SID')) {
            Schema::table('GL_GENERAL_RESERVATION', function (Blueprint $table) {
                DB::statement("UPDATE GL_GENERAL_RESERVATION
                                SET sid = 1
                                WHERE sid is null");
                $table->unsignedInteger('SID')->nullable(false)->default(1)->comment('GSSITE.SID;FK')->change();
            });    
        }
        if (Schema::hasColumn('GL_SEAT_SALE', 'SID')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                DB::statement("UPDATE GL_SEAT_SALE
                                SET sid = 1
                                WHERE sid is null");
                $table->unsignedInteger('SID')->nullable(false)->default(1)->comment('GSSITE.SID;FK')->change();
            });    
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'SID')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
                DB::statement("UPDATE GL_GMO_TRANS
                                SET sid = 1
                                WHERE sid is null");
                $table->unsignedInteger('SID')->nullable(false)->default(1)->comment('GSSITE.SID;FK')->change();
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
        if (Schema::hasColumn('GL_USER','SID')) {
            Schema::table('GL_USER', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->default(1)->comment('GSSITE.SID;FK')->change();
            });
        }
        if (Schema::hasColumn('GL_GENERAL_RESERVATION', 'SID')) {
            Schema::table('GL_GENERAL_RESERVATION', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->default(1)->comment('GSSITE.SID;FK')->change();
            });    
        }
        if (Schema::hasColumn('GL_SEAT_SALE', 'SID')) {
            Schema::table('GL_SEAT_SALE', function (Blueprint $table) {
                $table->unsignedInteger('SID')->nullable()->default(1)->comment('GSSITE.SID;FK')->change();
            });    
        }
        if (Schema::hasColumn('GL_GMO_TRANS', 'SID')) {
            Schema::table('GL_GMO_TRANS', function (Blueprint $table) {
        		$table->unsignedInteger('SID')->nullable()->default(1)->comment('GSSITE.SID;FK')->change();
            });    
        }
    }
}
