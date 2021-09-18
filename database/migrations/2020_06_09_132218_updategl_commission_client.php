<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateglCommissionClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_COMMISSION_CLIENT','rate')) {
            Schema::table('GL_COMMISSION_CLIENT', function (Blueprint $table) {
                $table->decimal('rate',4,2)->nullable(true)->default(null)->comment('料率')->change();
            });
        }

        if (Schema::hasColumn('GL_COMMISSION_CLIENT','amount')) {
            Schema::table('GL_COMMISSION_CLIENT', function (Blueprint $table) {
                $table->decimal('amount',5,2)->nullable(true)->default(null)->comment('手数料')->change();
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
        Schema::table('GL_COMMISSION_CLIENT', function (Blueprint $table) {
            //
            $table->decimal('rate',3,2)->nullable(true)->default(null)->change();
            $table->decimal('amount',6,2)->nullable(true)->default(null)->change();
        });
    }
}
