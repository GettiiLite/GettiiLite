<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGlvseatstatisofstage extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('GL_V_Seat_Static_of_Stage')) {
            DB::statement("DROP VIEW GL_V_Seat_Static_of_Stage");
        }

        if (!Schema::hasTable('GL_V_Seat_Static_of_Stage')) {
            
            DB::statement(" 
                CREATE VIEW GL_V_Seat_Static_of_Stage AS
                    (SELECT 
                        `s1`.`schedule_id` AS `schedule_id`,
                        `s1`.`RES` AS `RES`,
                        `s1`.`SALE` AS `SALE`,
                        `s1`.`UNSET` AS `UNSET`,
                        `nss`.`stock_limit` AS `stock_limit`
                    FROM
                        (SELECT 
                            `p1`.`schedule_id` AS `schedule_id`,
                                SUM(CASE
                                    WHEN `p1`.`seat_type` = 'R' THEN `p1`.`num`
                                    ELSE 0
                                END) AS `RES`,
                                SUM(CASE
                                    WHEN `p1`.`seat_type` = 'S' THEN `p1`.`num`
                                    ELSE 0
                                END) AS `SALE`,
                                SUM(CASE
                                    WHEN `p1`.`seat_type` = 'U' THEN `p1`.`num`
                                    ELSE 0
                                END) AS `UNSET`
                        FROM
                            (SELECT 
                            `t`.`schedule_id` AS `schedule_id`,
                                `t`.`seat_type` AS `seat_type`,
                                COUNT(`t`.`seat_type`) AS `num`
                        FROM
                            (SELECT 
                            `GL_V_Seat_of_Stage`.`schedule_id` AS `schedule_id`,
                                `GL_V_Seat_of_Stage`.`alloc_seat_id` AS `alloc_seat_id`,
                                CASE
                                    WHEN `GL_V_Seat_of_Stage`.`reserve_code` IS NOT NULL THEN 'R'
                                    WHEN `GL_V_Seat_of_Stage`.`seat_class_id` IS NOT NULL THEN 'S'
                                    ELSE 'U'
                                END AS `seat_type`
                        FROM
                            `GL_V_Seat_of_Stage`) AS `t`
                        GROUP BY `t`.`schedule_id` , `t`.`seat_type`) `p1`
                        GROUP BY `p1`.`schedule_id`) AS `s1`
                            LEFT JOIN
                            (
                                SELECT 
                                    schedule_id
                                    ,SUM(stock_limit) as stock_limit
                                FROM
                                    `GL_NONRESERVED_STOCK` 
                                GROUP BY schedule_id
                            ) AS `nss` 
                        ON (`nss`.`schedule_id` = `s1`.`schedule_id`)) UNION (SELECT 
                        `schedule_id`,
                        0 AS `RES`,
                        '0' AS `SALE`,
                        '0' AS `UNSET`,
                        SUM(`GL_NONRESERVED_STOCK`.`stock_limit`) AS `stock_limit`
                    FROM
                        `GL_NONRESERVED_STOCK`
                    WHERE
                        `schedule_id` NOT IN (SELECT DISTINCT
                                `schedule_id`
                            FROM
                                `GL_V_Seat_of_Stage`)
                        GROUP BY schedule_id
                    ) ORDER BY `schedule_id`
            ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('GL_V_Seat_Static_of_Stage')) {
            DB::statement("DROP VIEW GL_V_Seat_Static_of_Stage");
        }
        
        if (!Schema::hasTable('GL_V_Seat_Static_of_Stage')) {
            
            DB::statement(" 
                CREATE VIEW GL_V_Seat_Static_of_Stage AS
                    (SELECT 
                        `s1`.`schedule_id` AS `schedule_id`,
                        `s1`.`RES` AS `RES`,
                        `s1`.`SALE` AS `SALE`,
                        `s1`.`UNSET` AS `UNSET`,
                        `nss`.`stock_limit` AS `stock_limit`
                    FROM
                        (SELECT 
                            `p1`.`schedule_id` AS `schedule_id`,
                                SUM(CASE
                                    WHEN `p1`.`seat_type` = 'R' THEN `p1`.`num`
                                    ELSE 0
                                END) AS `RES`,
                                SUM(CASE
                                    WHEN `p1`.`seat_type` = 'S' THEN `p1`.`num`
                                    ELSE 0
                                END) AS `SALE`,
                                SUM(CASE
                                    WHEN `p1`.`seat_type` = 'U' THEN `p1`.`num`
                                    ELSE 0
                                END) AS `UNSET`
                        FROM
                            (SELECT 
                            `t`.`schedule_id` AS `schedule_id`,
                                `t`.`seat_type` AS `seat_type`,
                                COUNT(`t`.`seat_type`) AS `num`
                        FROM
                            (SELECT 
                            `GL_V_Seat_of_Stage`.`schedule_id` AS `schedule_id`,
                                `GL_V_Seat_of_Stage`.`alloc_seat_id` AS `alloc_seat_id`,
                                CASE
                                    WHEN `GL_V_Seat_of_Stage`.`reserve_code` IS NOT NULL THEN 'R'
                                    WHEN `GL_V_Seat_of_Stage`.`seat_class_id` IS NOT NULL THEN 'S'
                                    ELSE 'U'
                                END AS `seat_type`
                        FROM
                            `GL_V_Seat_of_Stage`) AS `t`
                        GROUP BY `t`.`schedule_id` , `t`.`seat_type`) `p1`
                        GROUP BY `p1`.`schedule_id`) AS `s1`
                            LEFT JOIN
                        `GL_NONRESERVED_STOCK` `nss` ON (`nss`.`schedule_id` = `s1`.`schedule_id`)) UNION (SELECT 
                        `schedule_id`,
                        0 AS `RES`,
                        '0' AS `SALE`,
                        '0' AS `UNSET`,
                        `stock_limit` AS `stock_limit`
                    FROM
                        `GL_NONRESERVED_STOCK`
                    WHERE
                        `schedule_id` NOT IN (SELECT DISTINCT
                                `schedule_id`
                            FROM
                                `GL_V_Seat_of_Stage`)) ORDER BY `schedule_id`
            ");
        }
    }
}