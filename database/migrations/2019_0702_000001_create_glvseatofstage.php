<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class glvseatofstage extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('GL_V_Seat_of_Stage')) {
            
            DB::statement("CREATE  VIEW GL_V_Seat_of_Stage AS
                (SELECT 
                    sc.schedule_id AS schedule_id,
                    se.alloc_seat_id AS alloc_seat_id,
                    se.performance_id AS performance_id,
                    se.seat_id AS seat_id,
                    se.seat_class_id AS seat_class_id,
                    se.reserve_code AS reserve_code
                FROM
                    ((GL_SCHEDULE sc
                    JOIN GL_SEAT se)
                    LEFT JOIN GL_STAGE_SEAT ss ON (ss.schedule_id = sc.schedule_id
                        AND ss.alloc_seat_id = se.alloc_seat_id))
                WHERE
                    se.performance_id = sc.performance_id
                        AND ss.stage_seat_id IS NULL) UNION ALL (SELECT 
                    sc.schedule_id AS schedule_id,
                    se.alloc_seat_id AS alloc_seat_id,
                    se.performance_id AS performance_id,
                    se.seat_id AS seat_id,
                    ss.seat_class_id AS seat_class_id,
                    ss.reserve_code AS reserve_code
                FROM
                    ((GL_SCHEDULE sc
                    JOIN GL_SEAT se)
                    JOIN GL_STAGE_SEAT ss)
                WHERE
                    se.performance_id = sc.performance_id
                        AND ss.schedule_id = sc.schedule_id
                        AND ss.alloc_seat_id = se.alloc_seat_id)
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
        if (Schema::hasTable('GL_V_Seat_of_Stage')) {
            DB::statement("DROP VIEW GL_V_Seat_of_Stage");
        }
    }
}