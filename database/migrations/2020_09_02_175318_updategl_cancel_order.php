<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateglCancelOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('GL_CANCEL_ORDER','refund_payment')) {
            Schema::table('GL_CANCEL_ORDER', function (Blueprint $table) {
                $table->unsignedDecimal('refund_payment', 10, 2)->nullable(false)->default(0)->comment('退款金額')->change();
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
        if (Schema::hasColumn('GL_CANCEL_ORDER','refund_payment')) {
            Schema::table('GL_CANCEL_ORDER', function (Blueprint $table) {
                $table->unsignedDecimal('refund_payment', 6, 2)->nullable(false)->default(0)->comment('退款金額')->change();
            });
        }
    }
}
