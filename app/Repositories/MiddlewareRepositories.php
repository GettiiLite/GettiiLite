<?php

namespace App\Repositories;

use App;
use Log;
use Exception;
use App\Models\EvenManageModel;
use App\Models\ScheduleModel;
use App\Models\UserManageModel;
use App\Models\UserAccountModel;
use App\Models\SeatSaleModel; //STS 2021/09/08 Task 48 No.2
use App\Models\GeneralReservationModel; //STS 2021/09/08 Task 48 No.2

class MiddlewareRepositories
{
    /** @var UserManageModel */
    protected $UserManageModel;
    /** @var ScheduleModel*/
    protected $ScheduleModel;
    /** @var UserAccountModel*/
    protected $UserAccountModel;
    /** @var SeatSaleModel*/
    protected $SeatSaleModel;
    /** @var GeneralReservationModel*/
    protected $GeneralReservationModel;

    /**
     * MiddlewareRepositories constructor.
     * @param EvenManageModel $EvenManageModel
     * 
     */
    public function __construct(EvenManageModel $EvenManageModel, ScheduleModel $ScheduleModel, UserManageModel $UserManageModel, UserAccountModel $UserAccountModel,
    SeatSaleModel $SeatSaleModel,
    GeneralReservationModel $GeneralReservationModel
    )//STS 2021/09/08 Task 48 No.2 add SeatSaleModel, GeneralReservationModel
    {
        $this->EvenManageModel = $EvenManageModel;
        $this->ScheduleModel = $ScheduleModel;
        $this->UserManageModel = $UserManageModel;        
        $this->UserAccountModel = $UserAccountModel;      
        $this->SeatSaleModel = $SeatSaleModel;        //STS 2021/09/08 Task 48 No.2
        $this->GeneralReservationModel = $GeneralReservationModel;     //STS 2021/09/08 Task 48 No.2
    }
    /**
     * get performance GLID
     * @param performance_Id
     * @return GLID
     */ 
    public function getPerformanceGLID($performanceId){   
        try{
            $result = $this->EvenManageModel->select(
                                                        'GLID',
                                                        'status'
                                                    )
                                            ->where('performance_id', $performanceId)
                                            ->get();
            return $result;
        }catch(Exception $e){
            Log::info('function getPerformanceGLID| MiddlewareRepositories | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * get schedule GLID
     * @param schedule_id
     * @return GLID
     */ 
    public function getScheduleGLID($scheduleId){   
        try{
            $result = $this->ScheduleModel->select('GL_PERFORMANCE.GLID')
                                          ->leftJoin('GL_PERFORMANCE', 'GL_PERFORMANCE.performance_id', '=', 'GL_SCHEDULE.performance_id')
                                          ->where('GL_SCHEDULE.schedule_id', '=', $scheduleId)
                                          ->get();
            return $result;
        }catch(Exception $e){
            Log::info('function getScheduleGLID| MiddlewareRepositories | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }

    /**
     * get login account status
     * @param accCd
     * @return UserAccountModel
     */ 
    public function getUserStatus(){   
        try{
            $result = $this->UserManageModel->where('GLID', session('GLID'))
                                             ->get();
            return $result;
        }catch(Exception $e){
            Log::info('function getUserStatus| MiddlewareRepositories | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }

    /**
     * get login account status
     * @param accCd
     * @return UserAccountModel
     */ 
    public function getAccStatus($accCd){   
        try{
            $result = $this->UserAccountModel->where('account_cd', $accCd)
                                          ->get();
            return $result;
        }catch(Exception $e){
            Log::info('function getAccStatus| MiddlewareRepositories | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * get order by seat_sale_id STS 2021/09/10 Task 48 No.2 
     * @param seatSaleId
     * @return orderInf
     */ 
    public function getOrder($seatSaleId){   
        try{
            $result = $this->SeatSaleModel->select('order_id')
                                            ->with(['generalReservation' => function ($query) {
                                                $query->select(
                                                    'order_id',
                                                    'GLID'
                                                    );
                                                }
                                            ])
                                            ->where('seat_sale_id', $seatSaleId)
                                          ->get();
            return $result[0]->toArray();
        }catch(Exception $e){
            Log::info('function getAccStatus| MiddlewareRepositories | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }


}
