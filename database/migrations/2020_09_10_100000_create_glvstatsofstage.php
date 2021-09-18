<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlVStatsofStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('GL_V_Stats_of_Stage')) {
            DB::statement("DROP VIEW GL_V_Stats_of_Stage");
        }

        DB::statement(" 
            CREATE VIEW `GL_V_Stats_of_Stage` AS
            SELECT 
                `sch`.`performance_id` AS `performance_id`,
                `sch`.`schedule_id` AS `schedule_id`,
                `ss`.`s_schedule_id` AS `s_schedule_id`,
                `ss`.`cnt_inpay_rev` AS `cnt_inpay_rev`,
                `ss`.`cnt_inpay_free` AS `cnt_inpay_free`,
                `ss`.`cnt_sale_rev` AS `cnt_sale_rev`,
                `ss`.`cnt_sale_free` AS `cnt_sale_free`,
                `ss`.`cnt_rev_issue` AS `cnt_rev_issue`,
                `ss`.`subtotal` AS `subtotal`
            FROM
                (`GL_SCHEDULE` `sch`
                LEFT JOIN (SELECT 
                    `ss`.`schedule_id` AS `s_schedule_id`,
                        SUM(CASE
                            WHEN
                                (`ss`.`seat_status` = 2
                                    AND `ss`.`sale_type` = 0
                                    AND `gr`.`reserve_expire` >= CURRENT_TIMESTAMP()
                                    AND `ss`.`alloc_seat_id` IS NOT NULL)
                            THEN
                                1
                            ELSE 0
                        END) AS `cnt_inpay_rev`,
                        SUM(CASE
                            WHEN
                                (`ss`.`seat_status` = 2
                                    AND `ss`.`sale_type` IN (0 , 1)
                                    AND `gr`.`reserve_expire` >= CURRENT_TIMESTAMP()
                                    AND `ss`.`alloc_seat_id` IS NULL)
                            THEN
                                1
                            ELSE 0
                        END) AS `cnt_inpay_free`,
                        SUM(CASE
                            WHEN
                                (`ss`.`seat_status` = 3
                                    AND `ss`.`sale_type` = 0
                                    AND `ss`.`alloc_seat_id` IS NOT NULL)
                            THEN
                                1
                            ELSE 0
                        END) AS `cnt_sale_rev`,
                        SUM(CASE
                            WHEN
                                (`ss`.`seat_status` = 3
                                    AND `ss`.`sale_type` IN (0 , 1)
                                    AND `ss`.`alloc_seat_id` IS NULL)
                            THEN
                                1
                            ELSE 0
                        END) AS `cnt_sale_free`,
                        SUM(CASE
                            WHEN
                                (`ss`.`seat_status` = 3
                                    AND `ss`.`sale_type` = 1
                                    AND `ss`.`alloc_seat_id` IS NOT NULL)
                            THEN
                                1
                            ELSE 0
                        END) AS `cnt_rev_issue`,
                        SUM(CASE
                            WHEN
                                (`ss`.`seat_status` = 3
                                    AND `ss`.`sale_type` = 0)
                            THEN
                                `ss`.`sale_price`
                            ELSE 0
                        END) AS `subtotal`
                FROM
                    (`GL_SEAT_SALE` `ss`
                LEFT JOIN `GL_GENERAL_RESERVATION` `gr` ON (`gr`.`order_id` = `ss`.`order_id`))
                GROUP BY `ss`.`schedule_id`) `ss` ON (`ss`.`s_schedule_id` = `sch`.`schedule_id`))
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('GL_V_Stats_of_Stage')) {
            DB::statement("DROP VIEW GL_V_Stats_of_Stage");
        }
    }
}