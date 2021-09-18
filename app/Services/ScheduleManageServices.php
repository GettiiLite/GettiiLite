<?php

namespace App\Services;

use Log;
use Exception;
use App;
use App\Repositories\ScheduleManageRepositories;
use App\Services\EvenManageServices;
use Carbon\Carbon;
use App\Services\MiddlewareServices; // STS 2021/09/15 Task 48.2.2

class ScheduleManageServices
{
    /** @var ScheduleManageServices */
    protected $ScheduleManageRepositories;
    /** @var MiddlewareServices */
    protected $MiddlewareServices;

    /**
     * UserController constructor.
     * @param ScheduleManageServices $UserManagerServices
     * @param EvenManageServices $EvenManageServices
     * @param MiddlewareServices $middlewareServices STS 2021/09/15 Task 48.2.2 add
     * @return
     */
    public function __construct(ScheduleManageRepositories $ScheduleManageRepositories, EvenManageServices $EvenManageServices, MiddlewareServices $MiddlewareServices)
    {
        $this->ScheduleManageRepositories = $ScheduleManageRepositories;
        $this->EvenManageServices         = $EvenManageServices;
        $this->MiddlewareServices         = $MiddlewareServices;
    }  
    /**
     * schedule list 
     * @param  $performanceId
     * @return $json
     */
    public function scheduleList($performanceId, $scheduleId){
        try{
            $performanceData    = [];
            $scheduleData       = [];
           
            $data = array(
                'performance_id' =>  $performanceId 
            );

            if(session()->exists('schedule_cancel_result')){
                $schedule_cancel_result  = true;
                $schedule_cancel_mss     = session('schedule_cancel_result');
                session()->forget('schedule_cancel_result');
            }else{
                $schedule_cancel_result = false;
                $schedule_cancel_mss    = '';
            }

            $performanceInf = $this->ScheduleManageRepositories->getPerformanceData($data);
            $scheduleInf    = $this->ScheduleManageRepositories->getScheduleData($data);

            foreach($performanceInf as $value){
                $performanceData[] = array(
                    'performance_id'        =>  $value['performance_id'],
                    'performance_name'      =>  $value['performance_name'],
                    'hall_disp_name'        =>  $value['hall_disp_name'],
                    'performance_st_dt'     =>  $value['performance_st_dt'],
                    'performance_end_dt'    =>  $value['performance_end_dt'],
                );
            }

            $scheduleStatuc = array(
                'schedule_id'   => $scheduleId,
            );

            foreach($scheduleInf as $value){
                if($scheduleId == $value['schedule_id']){
                    $check_flg = true;
                }else{
                    $check_flg = $value['cancel_flg'];
                }

                $scheduleData[] = array(
                    'check_flg'             =>  $check_flg,
                    'schedule_id'           =>  $value['schedule_id'],
                    'disp_performance_date' =>  $value['disp_performance_date'],
                    'performance_date'      =>  $value['performance_date'],
                    'start_time'            =>  Carbon::parse($value['start_time'])->format('H:i'),
                    'cancel_messgae'        =>  $value['cancel_messgae'],
                    'cancel_flg'            =>  $value['cancel_flg'],
                    'refund_st_date'        =>  $value['refund_st_date'],
                    'refund_end_date'       =>  $value['refund_end_date'],
                );
            }

            $result_statuc = array(
                'schedule'                  =>  $scheduleStatuc,
                'schedule_cancel_result'    =>  $schedule_cancel_result,
                'schedule_cancel_mss'       =>  $schedule_cancel_mss,

            );
            
            $result_data = array(
                'performance'       =>  $performanceData,
                'performance_json'  =>  json_encode($performanceData),
                'schedule'          =>  json_encode($scheduleData),
            );
           
            $result = array(
                'statuc'    =>  $result_statuc,
                'data'      =>  $result_data,
            );
           

            return $result;
        }catch(Exception $e){
            Log::info('function scheduleList | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    } 
    /**
     * change schedule status to cancel 
     * @param $request
     * @return $result
     */
    public function cancelSchedule(array $request){
        try{
            $account_cd     = session('account_cd');
            $json           = json_decode($request['json'], true);
            $status         = $json[0]['status'][0];
            $performance    = $json[0]['data'][0]['performance'][0];
            $schedule       = $json[0]['data'][0]['schedule'];
            $url            = '/schedule/list/'.$performance['performance_id'].'/0';
            // STS 2021/09/15 Task 48.2.2 start
            if(session('admin_flg')){
                foreach($schedule as $value){
                    $result = $this->MiddlewareServices->checkScheduleId($value['schedule_id']);
                    if(!$result){
                        Log::info('cancelSchedule');
                        App::abort(404);
                    }
                } 
            }
            // STS 2021/09/15 Task 48.2.2 end
            if($status['allCheck']){
                $data = array(
                    'performance_id'    =>  $performance['performance_id'],
                    'status'            =>  \Config::get('constant.performance_status.cancel'),
                    'update_account_cd' =>  $account_cd,
                );

                $this->ScheduleManageRepositories->changePerformanceStatus($data);
            }

            foreach($schedule as $value){
                $data = array(
                    'schedule_id'       =>  $value['schedule_id'],
                    'cancel_flg'        =>  $value['check_flg'],
                    'cancel_messgae'    =>  $value['cancel_messgae'],
                    'refund_st_date'    =>  ($value['refund_st_date'])?$value['refund_st_date']:null,
                    'refund_end_date'   =>  ($value['refund_end_date'])?$value['refund_end_date']:null,
                    'cancel_account_cd' =>  $account_cd,
                    'update_account_cd' =>  $account_cd,
                );

                $this->ScheduleManageRepositories->changeScheduleStatuc($data);
            }
            
            session([
                'schedule_cancel_result' => true,
            ]);

           $this->EvenManageServices->transportPerfomanceInfo($performance['performance_id']);

            return $url;
        }catch(Exception $e){
            Log::info('function getPerformanceData | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
}