<?php

namespace App\Repositories;

use App;
use Log;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralReservationModel;
use App\Models\SeatSaleModel;
use App\Models\UserManageModel;
use App\Models\GSSiteModel;
use App\Models\MemberModel;//STS task 45 11/08-2021

class MemberRepositories
{
    /** @var GeneralReservationModel */
    protected $GeneralReservationModel;
    /** @var SeatSaleModel */
    protected $SeatSaleModel;
    /** @var UserManageModel */
    protected $userManageModel;
    /** @var GSSiteModel */
    protected $gsSiteModel;
     /** @var MemberModel *///STS task 45 11/08-2021
    protected $MemberModel;//STS task 45 11/08-2021

    /**
     * MiddlewareRepositories constructor.
     * @param GeneralReservationModel $GeneralReservationModel
     * @param SeatSaleModel $SeatSaleModel
     * 
     */
    // public function __construct(GeneralReservationModel $GeneralReservationModel, SeatSaleModel $SeatSaleModel, UserManageModel $userManageModel, GSSiteModel $gsSiteModel)
    public function __construct(GeneralReservationModel $GeneralReservationModel, SeatSaleModel $SeatSaleModel, UserManageModel $userManageModel, GSSiteModel $gsSiteModel, MemberModel $MemberModel)//STS task 45 11/08-2021
    {
        $this->GeneralReservationModel = $GeneralReservationModel;   
        $this->SeatSaleModel           = $SeatSaleModel;
        $this->userManageModel         = $userManageModel;
        $this->gsSiteModel             = $gsSiteModel;
        $this->MemberModel             = $MemberModel;//STS task 45 11/08-2021
    }
    /**
     *取得 GeneralReservation 中會員 id
     * @param array $data
     * @return array $result
     */ 
    public function getOrderUserId($data){   
        try{
            $result = $this->GeneralReservationModel->select(
                                                        'member_id'
                                                        )
                                                    ->where('reserve_no', $data['reserve_no'])
                                                    ->get()
                                                    ->toArray();
            return $result;
        }catch(Exception $e){
            Log::info('function getOrderUserId| '.$e->getMessage());
            App::abort(404);
        }
    }
   /**
     * 取得訂單資料，關鍵字過濾
     * 
     * @param array $data
     * @return array $result
     */ 
    public function getOrderDataById($data){   
        try{
            $user = $this->userManageModel->findOrFail($data['glid']);
            $result = [];
            if($user->SID) {
                $result = $this->GeneralReservationModel->select(
                    'order_id',
                    'reserve_no',
                    'reserve_date',
                    'reserve_expire',
                    'pay_method',
                    'pickup_method',
                    'cancel_flg'
                    )
                ->where('member_id', $data['member_id'])
                ->where('reserve_no', 'like', '%'.$data['keyWord'].'%')
                ->where('SID', $user->SID)
                ->get()
                ->toArray();
            }
            return $result;
        }catch(Exception $e){
            Log::info('function getOrderDataById| '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * 取得訂單資料
     * 
     * @param array $data
     * @return array $result
     */ 
    public function getOrderData($data){   
        try{
            $user = $this->userManageModel->findOrFail($data['glid']);
            $result = [];
            if($user->SID) {
                $result = $this->GeneralReservationModel->select(
                                                            'order_id',
                                                            'reserve_no',
                                                            'reserve_date',
                                                            'reserve_expire',
                                                            'pay_method',
                                                            'pickup_method',
                                                            'cancel_flg'
                                                            )
                                                        ->where('member_id', $data['member_id'])
                                                        ->where('SID', $user->SID)
                                                        ->orderBy('order_id', 'desc')
                                                        ->get()
                                                        ->toArray();
            }
            return $result;
        }catch(Exception $e){
            Log::info('function getOrderData| '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     * 取得票資料
     * 
     * @param array $data
     * @return array $result
     */ 
    public function getSeatSaleData($data){   
        try{
            $result = $this->SeatSaleModel->select(
                                                'GL_SEAT_SALE.seat_sale_id',
                                                'GL_SEAT_SALE.alloc_seat_id',
                                                'GL_SEAT_SALE.schedule_id',
                                                'GL_SEAT_SALE.seat_status',
                                                'GL_SEAT_SALE.sale_price',
                                                'GL_SEAT_SALE.seat_class_name',
                                                'GL_SEAT_SALE.seat_class_id',
                                                'GL_SEAT_SALE.payment_flg',
                                                'GL_SEAT_SALE.issue_flg',
                                                'GL_GENERAL_RESERVATION.commission_payment',
                                                'GL_GENERAL_RESERVATION.commission_ticket',
                                                'GL_GENERAL_RESERVATION.commission_delivery',
                                                'GL_GENERAL_RESERVATION.commission_sub',
                                                'GL_GENERAL_RESERVATION.commission_uc',
                                                'GL_SCHEDULE.open_date',
                                                'GL_SCHEDULE.performance_date',
                                                'GL_SCHEDULE.start_time',                                
                                                'GL_PERFORMANCE.performance_name',
                                                'GL_PERFORMANCE.status'
                                            )
                                            ->leftJoin('GL_GENERAL_RESERVATION','GL_SEAT_SALE.order_id','=','GL_GENERAL_RESERVATION.order_id')
                                            ->leftJoin('GL_SCHEDULE','GL_SEAT_SALE.schedule_id','=','GL_SCHEDULE.schedule_id')
                                            ->leftJoin('GL_PERFORMANCE','GL_SCHEDULE.performance_id','=','GL_PERFORMANCE.performance_id');
               
                if($data['search'] == 'search'){
                    if($data['orderStatus']){
                        $result =  $result->where('GL_SEAT_SALE.seat_status', '=', $data['orderStatus']);
                    }

                    if($data['keyWord'] && !$data['had_reserve_no']){
                        $result =  $result->where('GL_PERFORMANCE.performance_name', 'like', '%'.$data['keyWord'].'%');
                    }
                }   
                
                $result =  $result->where('GL_SEAT_SALE.order_id', $data['order_id'])
                                  ->get()
                                  ->toArray();
                                   
            return $result;
        }catch(Exception $e){
            Log::info('function getSeatSaleData| '.$e->getMessage());
            App::abort(404);
        }
    }
    /**
     *  取得訂單資料
     * 
     * @param array $data
     * @return array $result
     */
    public function getOrdersDetails($data){
        try{
            $result = $this->GeneralReservationModel->select(
                'GL_GENERAL_RESERVATION.order_id',
                'GL_GENERAL_RESERVATION.reserve_no',
                'GL_GENERAL_RESERVATION.reserve_date',
                'GL_GENERAL_RESERVATION.pay_method',
                'GL_GENERAL_RESERVATION.pickup_method',
                'GL_GENERAL_RESERVATION.pickup_st_date',
                'GL_GENERAL_RESERVATION.pickup_due_date',
                'GL_GENERAL_RESERVATION.cancel_flg',
                'GL_GENERAL_RESERVATION.comment',
                'GL_GENERAL_RESERVATION.commission_sv',
                'GL_GENERAL_RESERVATION.commission_payment',
                'GL_GENERAL_RESERVATION.commission_ticket',
                'GL_GENERAL_RESERVATION.commission_delivery',
                'GL_GENERAL_RESERVATION.commission_sub',
                'GL_GENERAL_RESERVATION.commission_uc',
                'GL_GENERAL_RESERVATION.reserve_expire',
                'GL_SEAT_SALE.seat_sale_id',
                'GL_SEAT_SALE.alloc_seat_id',
                'GL_SEAT_SALE.schedule_id',
                'GL_SEAT_SALE.seat_status',
                'GL_SEAT_SALE.sale_price',
                'GL_SEAT_SALE.commission_sv as seat_sale_commission_sv',
                'GL_SEAT_SALE.commission_payment as seat_sale_commission_payment',
                'GL_SEAT_SALE.commission_ticket as seat_sale_commission_ticket',
                'GL_SEAT_SALE.commission_delivery as seat_sale_commission_delivery',
                'GL_SEAT_SALE.commission_sub as seat_sale_commission_sub',
                'GL_SEAT_SALE.commission_uc as seat_sale_commission_uc',
                'GL_SEAT_SALE.ticket_class_name',
                'GL_SEAT_SALE.seat_class_name',
                'GL_SEAT_SALE.seat_class_id',
                'GL_SEAT_SALE.payment_flg',
                'GL_SEAT_SALE.payment_date',
                'GL_SEAT_SALE.issue_flg',
                'GL_SCHEDULE.open_date',
                'GL_SCHEDULE.performance_date',
                'GL_SCHEDULE.start_time',
                'GL_PERFORMANCE.performance_name',
                'GL_PERFORMANCE.status',
                'GL_PERFORMANCE.seatmap_profile_cd',
                'GL_FLOOR.floor_name',
                'GL_BLOCK.block_name',
                'GL_HALL_SEAT.seat_cols',
                'GL_HALL_SEAT.seat_number'
            )
            ->leftJoin('GL_SEAT_SALE','GL_GENERAL_RESERVATION.order_id','=','GL_SEAT_SALE.order_id')
            ->leftJoin('GL_SCHEDULE','GL_SEAT_SALE.schedule_id','=','GL_SCHEDULE.schedule_id')
            ->leftJoin('GL_PERFORMANCE','GL_SCHEDULE.performance_id','=','GL_PERFORMANCE.performance_id')
            ->leftJoin('GL_SEAT','GL_SEAT_SALE.alloc_seat_id','=','GL_SEAT.alloc_seat_id')
            ->leftJoin('GL_HALL_SEAT','GL_SEAT.seat_id','=','GL_HALL_SEAT.seat_id')
            ->leftJoin('GL_FLOOR','GL_HALL_SEAT.floor_id','=','GL_FLOOR.floor_id')
            ->leftJoin('GL_BLOCK','GL_HALL_SEAT.block_id','=','GL_BLOCK.block_id');

        
            $result =  $result->where('GL_GENERAL_RESERVATION.reserve_no', $data['reserve_no'])
                              ->get()
                              ->toArray();
            
            return $result;

        }catch(Exception $e){
            Log::info('function getOrdersDetails| '.$e->getMessage());
            App::abort(404);
        }
    }

    /**
     *  取得訂單資料
     * 
     * @param string $glid
     * @return array $result
     */
    public function getAPISite($glid){
        try{
            $user = $this->userManageModel->findOrFail($glid);
            if($user->SID) {
                $gssite = $this->gsSiteModel->findOrFail($user->SID);
                if($gssite->url_api)
                    return $gssite->url_api;
                else
                    throw new Exception("Can't get setting of api site.");
            }
            throw new Exception("Can't get setting of api site.");
        }catch(Exception $e){
            Log::info('function getAPISite: '.$e->getMessage());
            App::abort(404);
        }
    }

    public function getSIDbyGLID($glid) 
    {
        $user = $this->userManageModel->findOrFail($glid);
        return $user->SID;
    }
    /**
     * 取得票資料
     * 
     * @param array $data
     * @return array $result
     */ 
 //STS task 45 11/08-2021 start
    public function getMemberData($member_id){
          try{
            $result = $this->MemberModel->select(
                'GL_MEMBER.tel_num',
                'GL_MEMBER.mail_address',
                'GL_MEMBER.allow_email',
                'GL_MEMBER.status',
                'GL_MEMBER.system_kbn'
                )
            ->where('member_id', $member_id)
            ->get()
            ->toArray();
            return $result;
        }catch(Exception $e){
            Log::info('function getMemberData| '.$e->getMessage());
            App::abort(404);
        }
    }
    //STS task 45 11/08-2021 end
}
