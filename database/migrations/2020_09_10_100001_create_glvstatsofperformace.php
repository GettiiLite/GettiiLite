<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlvstatsofperformace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_V_Stats_of_Performace')) {
            DB::statement("DROP VIEW GL_V_Stats_of_Performace");
        }

        DB::statement(" 
            CREATE VIEW `GL_V_Stats_of_Performace` AS
            SELECT 
                `gp`.`performance_id` AS `performance_id`,
                `gp`.`status` AS `status`,
                `vsp`.`s_pid` AS `s_pid`,
                `vsp`.`cnt_inpay_rev` AS `cnt_inpay_rev`,
                `vsp`.`cnt_inpay_free` AS `cnt_inpay_free`,
                `vsp`.`cnt_sale_rev` AS `cnt_sale_rev`,
                `vsp`.`cnt_sale_free` AS `cnt_sale_free`,
                `vsp`.`cnt_rev_issue` AS `cnt_rev_issue`,
                `vsp`.`subtotal` AS `subtotal`
            FROM
                (`GL_PERFORMANCE` `gp`
                LEFT JOIN (SELECT 
                    `vss`.`performance_id` AS `s_pid`,
                        SUM(`vss`.`cnt_inpay_rev`) AS `cnt_inpay_rev`,
                        SUM(`vss`.`cnt_inpay_free`) AS `cnt_inpay_free`,
                        SUM(`vss`.`cnt_sale_rev`) AS `cnt_sale_rev`,
                        SUM(`vss`.`cnt_sale_free`) AS `cnt_sale_free`,
                        SUM(`vss`.`cnt_rev_issue`) AS `cnt_rev_issue`,
                        SUM(`vss`.`subtotal`) AS `subtotal`
                FROM
                    `GL_V_Stats_of_Stage` `vss`
                GROUP BY `vss`.`performance_id`) `vsp` ON (`vsp`.`s_pid` = `gp`.`performance_id`))
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('GL_V_Stats_of_Performace')) {
            DB::statement("DROP VIEW GL_V_Stats_of_Performace");
        }
    }
}