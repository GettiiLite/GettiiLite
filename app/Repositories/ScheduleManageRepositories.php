<?php

namespace App\Repositories;

use App;
use Log;
use Exception;
use App\Models\EvenManageModel;
use App\Models\ScheduleModel;

class ScheduleManageRepositories
{
    /** @var ScheduleModel*/
    protected $ScheduleModel;

    /**
     * MiddlewareRepositories constructor.
     * @param EvenManageModel $EvenManageModel
     * @param ScheduleModel $ScheduleModel
     * 
     */
    public function __construct(EvenManageModel $EvenManageModel, ScheduleModel $ScheduleModel)
    {
        $this->EvenManageModel = $EvenManageModel;
        $this->ScheduleModel   = $ScheduleModel;        
    }
    /**
     * get performance data
     * @param  $data
     * @return $result
     */ 
    public function changePerformanceStatus($data){   
        try{
            $result = $this->EvenManageModel->where('performance_id', $data['performance_id'])
                                            ->update(
                                                    [
                                                        'status'        => $data['status'],
                                                        'update_account_cd'    => $data['update_account_cd']
                                                    ]
                                                );
           
            return $result;
        }catch(Exception $e){
            Log::info('function getPerformanceData | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * change schedule statuc
     * @param  $data
     * @return $result
     */ 
    public function changeScheduleStatuc($data){   
        try{
            $result = $this->ScheduleModel->where('schedule_id', $data['schedule_id'])
                                          ->update(
                                                [
                                                    'cancel_flg'        => $data['cancel_flg'],
                                                    'cancel_messgae'    => $data['cancel_messgae'],
                                                    'refund_st_date'    => $data['refund_st_date'],
                                                    'refund_end_date'   => $data['refund_end_date'],
                                                    'cancel_account_cd' => $data['cancel_account_cd'],
                                                    'update_account_cd' => $data['update_account_cd']
                                                ]
                                            );
            return $result;
        }catch(Exception $e){
            Log::info('function changeScheduleStatuc | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * get performance data
     * @param  $data
     * @return $result
     */ 
    public function getPerformanceData($data){   
        try{
            $result = $this->EvenManageModel->select(
                                                        'performance_id',
                                                        'performance_name',
                                                        'hall_disp_name',
                                                        'performance_st_dt',
                                                        'performance_end_dt'
                                                    )
                                            ->where('performance_id', $data['performance_id'])
                                            ->get();
            return $result;
        }catch(Exception $e){
            Log::info('function getPerformanceData | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * get schedule data
     * @param  $data
     * @return $result
     */ 
    public function getScheduleData($data){   
        try{
            $result = $this->ScheduleModel->select(
                                                    'schedule_id',
                                                    'disp_performance_date',
                                                    'performance_date',
                                                    'start_time',
                                                    'cancel_messgae',
                                                    'cancel_flg',
                                                    'refund_st_date',
                                                    'refund_end_date'
                                                  )
                                            ->where('performance_id', $data['performance_id'])
                                            ->get();
            return $result;
        }catch(Exception $e){
            Log::info('function getSchedule | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
}
