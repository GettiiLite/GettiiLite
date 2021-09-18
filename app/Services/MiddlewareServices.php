<?php

namespace App\Services;

use Log;
use Exception;
use App;
use App\Repositories\MiddlewareRepositories;

class MiddlewareServices
{
    /** @var UserManagerServices */
    protected $MiddlewareRepositories;

    /**
     * UserController constructor.
     * @param MiddlewareServices $UserManagerServices
     * @return
     */
    public function __construct(MiddlewareRepositories $MiddlewareRepositories)
    {
        $this->MiddlewareRepositories = $MiddlewareRepositories;
    }   
    /**
     * cheack performance status is not delete
     * @param performance_Id
     * @return result
     */
    public function checkSuperUserPerformanceId($performanceId){
        try{
            $performance_status = \Config::get('constant.performance_status.delete');
            $result             = $this->MiddlewareRepositories->getPerformanceGLID($performanceId);
          
            if(isset($result[0])){
                if($result[0]['status'] !== $performance_status){
                    return true;
                }else{
                    throw new Exception ('GLID not equal');
                }
            }else{
                throw new Exception ('GLID is null');
            }
        }catch(Exception $e){
            Log::info('function checkSuperUserPerformanceId| MiddlewareServices | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * cheack performace GLID is equal with acount GLID 
     * @param performance_Id
     * @return result
     */
    public function checkPerformanceId($performanceId){
        try{
            $GLID               = session('GLID');
            $performance_status = \Config::get('constant.performance_status.delete');
            $result             = $this->MiddlewareRepositories->getPerformanceGLID($performanceId);
          
            if(isset($result[0])){
                if($GLID == $result[0]['GLID'] && $result[0]['status'] !== $performance_status){
                    return true;
                }else{
                    throw new Exception ('GLID not equal');
                }
            }else{
                throw new Exception ('GLID is null');
            }
        }catch(Exception $e){
            Log::info('function checkPerformanceId| MiddlewareServices | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * cheack schedule GLID is equal with acount GLID 
     * @param schedule_id
     * @return result
     */
    public function checkScheduleId($scheduleId){
        try{
            $GLID = session('GLID');
            $result = $this->MiddlewareRepositories->getScheduleGLID($scheduleId);
           
            if(isset($result[0])){
                if($GLID == $result[0]['GLID']){
                    return true;
                }else{
                    throw new Exception ('GLID not equal');
                }
            }else{
                throw new Exception ('GLID is null');
            }
        }catch(Exception $e){
            Log::info('function checkScheduleId| MiddlewareServices | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }

    /**
     * cheack Login account status 
     * @param account_cd
     * @return result
     */
    public function checkAccStatus($accCd){
        try{
            $user = $this->MiddlewareRepositories->getUserStatus();
            if($user[0]->user_status == -2)
            {
              //退会
              session()->flush();
              abort(404);
            }elseif($user[0]->user_status == 8){
              //退会申請
              //sessionの各権限を閲覧のみに変更
              (session('profile_info_flg') == 2) ? session(['profile_info_flg' => 1 ]) : '';
              (session('event_info_flg') == 2) ? session(['event_info_flg' => 1 ]) : '';
              (session('sales_info_flg') == 2) ? session(['sales_info_flg' => 1 ]) : '';
              session(['event_publishable' => 0 ]);            
            }
            $result = $this->MiddlewareRepositories->getAccStatus($accCd);
            // dd($result);

            if(isset($result[0])){
                if($result[0]->status != 1) 
                    return false;

                if($result[0]->expire_date < now() ) 
                    return false;
            }
            else {
                return false;
            }
           
            return true;
        }catch(Exception $e){
            Log::info('function checkAccStatus| MiddlewareServices | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }

    /**
     * Get GLID from order STS 2021/09/10 Task 48 No.2 
     * @param account_cd
     * @return result
     */
    public function getOrderGLID($seatSaleId){
        try{
            $order = $this->MiddlewareRepositories->getOrder($seatSaleId);
            $orderGLID = $order['general_reservation']['GLID'];
            return $orderGLID;
        }catch(Exception $e){
            Log::info('function getOrderGLID| MiddlewareServices | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }



}