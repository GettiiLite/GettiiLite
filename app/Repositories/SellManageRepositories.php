<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\EvenManageModel;
use App\Models\ScheduleModel;
use App\Models\SeatSaleModel;
use App\Models\SeatModel;
use App\Models\GeneralReservationModel;
use App\Models\ReserveModel;
use App\Models\SeatClassModel;
use App\Models\HallSeatModel;
use App\Models\BlockModel;
use App\Models\TicketClassModal;
use App\Models\VSeatOfStageModal;
use App\Models\StageSeatModal;
use App\Models\NonreservedStockModal;
use App\Models\CancelOrderModel;
use App\Models\StagenameModel;
use App\Models\SysReportModel;
use App\Models\AmountReviseModel;
use App\Models\UserAccountModel;
use App\Models\CommissionClientModel;
use App\Models\GLVStatsOfPerformaceModel;
use App\Models\GLVSeatStaticOfStageModel;
use App\Models\GLVStatsOfStageModel;
use App\Models\PortalMstOutputModel;
use App\Models\DraftModel;
use App\Models\UserManageModel;
use App\Models\QuestionModel;
use Exception;
use Log;
use App;
use Carbon\Carbon;

class SellManageRepositories
{
    /** @var EvenManageModel*/
    protected $EvenManageModel;
    /** @var ScheduleModel*/
    protected $ScheduleModel;
    /** @var SeatSaleModel*/
    protected $SeatSaleModel;
    /** @var SeatModel*/
    protected $SeatModel;
    /** @var GeneralReservationModel*/
    protected $GeneralReservationModel;
    /** @var ReserveModel */
    protected $ReserveModel;
    /** @var SeatClassModel*/
    protected $SeatClassModel;
    /** @var HallSeatModel*/
    protected $HallSeatModel;
    /** @var BlockModel*/
    protected $BlockModel;
    /** @var TicketClassModal*/
    protected $TicketClassModal;
    /** @var VSeatOfStageModal*/
    protected $VSeatOfStageModal;
    /** @var StageSeatModal*/
    protected $StageSeatModal;
    /** @var NonreservedStockModal*/
    protected $NonreservedStockModal;
    /** @var CancelOrderModel*/
    protected $CancelOrderModel;
    /** @var StagenameModel*/
    protected $StagenameModel;
    /** @var SysReportModel*/
    protected $SysReportModel;
    /** @var AmountReviseModel*/
    protected $AmountReviseModel;
    /** @var UserAccountModel*/
    protected $UserAccountModel;
    /** @var CommissionClientModel*/
    protected $CommissionClientModel;
    /** @var GLVStatsOfPerformaceModel*/
    protected $GLVStatsOfPerformaceModel;
    /** @var GLVSeatStaticOfStageModel*/
    protected $GLVSeatStaticOfStageModel;
    /** @var GLVStatsOfStageModel*/
    protected $GLVStatsOfStageModel;
    /** @var PortalMstOutputModel */
    protected $PortalMstOutputModel;
    /** @var DraftModel */
    protected $DraftModel;
    /** @var UserManageModel */
    protected $UserManageModel;
    /** @var QuestionModel*/
    protected $QuestionModel;
    /**
     * UserManageModel constructor.
     * @param UserManageModel $UserManageModel
     */
    public function __construct(EvenManageModel $EvenManageModel, ScheduleModel $ScheduleModel, SeatSaleModel $SeatSaleModel, SeatModel $SeatModel, GeneralReservationModel $GeneralReservationModel, ReserveModel $ReserveModel, SeatClassModel $SeatClassModel, HallSeatModel $HallSeatModel, BlockModel $BlockModel, TicketClassModal $TicketClassModal, VSeatOfStageModal $VSeatOfStageModal, StageSeatModal $StageSeatModal, NonreservedStockModal $NonreservedStockModal, CancelOrderModel $CancelOrderModel, StagenameModel $StagenameModel, SysReportModel $SysReportModel, AmountReviseModel $AmountReviseModel, UserAccountModel $UserAccountModel, CommissionClientModel $CommissionClientModel, GLVStatsOfPerformaceModel $GLVStatsOfPerformaceModel, GLVSeatStaticOfStageModel $GLVSeatStaticOfStageModel, GLVStatsOfStageModel $GLVStatsOfStageModel, PortalMstOutputModel $PortalMstOutputModel, DraftModel $DraftModel, UserManageModel $UserManageModel, QuestionModel $QuestionModel)
    {
        $this->EvenManageModel          = $EvenManageModel;
        $this->ScheduleModel            = $ScheduleModel;
        $this->SeatSaleModel            = $SeatSaleModel;
        $this->SeatModel                = $SeatModel;
        $this->GeneralReservationModel  = $GeneralReservationModel;
        $this->ReserveModel             = $ReserveModel;
        $this->SeatClassModel           = $SeatClassModel;
        $this->HallSeatModel            = $HallSeatModel;
        $this->BlockModel               = $BlockModel;
        $this->TicketClassModal         = $TicketClassModal;
        $this->VSeatOfStageModal        = $VSeatOfStageModal;
        $this->StageSeatModal           = $StageSeatModal;
        $this->NonreservedStockModal    = $NonreservedStockModal;
        $this->CancelOrderModel         = $CancelOrderModel;
        $this->StagenameModel           = $StagenameModel;
        $this->SysReportModel           = $SysReportModel;
        $this->AmountReviseModel        = $AmountReviseModel;
        $this->UserAccountModel         = $UserAccountModel;
        $this->CommissionClientModel    = $CommissionClientModel;
        $this->AmountReviseModel        = $AmountReviseModel;
        $this->UserAccountModel         = $UserAccountModel;
        $this->GLVStatsOfPerformaceModel= $GLVStatsOfPerformaceModel;
        $this->GLVSeatStaticOfStageModel= $GLVSeatStaticOfStageModel;
        $this->GLVStatsOfStageModel     = $GLVStatsOfStageModel;
        $this->PortalMstOutputModel     = $PortalMstOutputModel;
        $this->DraftModel               = $DraftModel;
        $this->UserManageModel          = $UserManageModel;
        $this->QuestionModel            = $QuestionModel;
    }
    private function getRespectiveData($mapData, $seat_type_count)
    {
        $respective_data = array();

        foreach($mapData->data[0]->mapData as $block_data){
            foreach($block_data->blockData as $seat_data){ 
                foreach($seat_data->seatData as $seat_inf){ 
                    if(isset($seat_inf->respectiveData)){
                        foreach($seat_inf->respectiveData as $respective_inf){ 
                            $date_value = $respective_inf->dateValue;
                            $rule_id = $respective_inf->ruleId;
                            if(!isset($respective_data[$date_value][$rule_id])){
                                $respective_data[$date_value][$rule_id] = $seat_type_count;
                            }
                            $now_respective = $respective_data[$date_value][$rule_id];
                            if(isset($seat_inf->typeData) && gettype($seat_inf->typeData) === 'object'){
                                switch($seat_inf->typeData->type){
                                    case 'specSeat':{ 
                                        switch($respective_inf->type){
                                            case 0:{
                                                $now_respective['UNSET']++;
                                                $now_respective['RES']--;
                                                break;
                                            }
                                            case 2:{
                                                $now_respective['SALE']++;
                                                $now_respective['RES']--;
                                                break;
                                            }
                                        }
                                        break;
                                    }
                                    case 'ticketSetting':{
                                        switch ($respective_inf->type) {
                                            case 0:{
                                                $now_respective['UNSET']++;
                                                $now_respective['SALE']--;
                                                break;
                                            }
                                            case 1:
                                                $now_respective['RES']++;
                                                $now_respective['SALE']--;
                                                break;
                                        }
                                        break;
                                    }
                                }
                            }else{
                                switch($respective_inf->type) {
                                    case 1:{
                                        $now_respective['RES']++;
                                        $now_respective['UNSET']--;
                                        break;
                                    }
                                    case 2:{
                                        $now_respective['SALE']++;
                                        $now_respective['UNSET']--;
                                        break;
                                    }
                                }
                            }
                            $respective_data[$date_value][$rule_id] = $now_respective;
                        }
                    }
                }
            }
        }

        return $respective_data;
    }
    private function getPerformanceDispStatus($data)
    {
      switch($data["status"]) {
        //中止
        case \Config::get('constant.performance_status.cancel'):  
          $disp_status = \Config::get('constant.performance_disp_status.cancel');
        break;
   
        //削除
        case \Config::get('constant.performance_status.delete') : 
          $disp_status = \Config::get('constant.performance_disp_status.deleted');
        break;
  
        //登録中
        case \Config::get('constant.performance_status.going') : 
          $disp_status = \Config::get('constant.performance_disp_status.going');
        break;
  
        //登録済
        case \Config::get('constant.performance_status.complete'): 
          $disp_status = \Config::get('constant.performance_disp_status.complete');
        break;
  
        //表示可
        case \Config::get('constant.performance_status.browse'): 
          // $status = ($value["trans_flg"] == '0')? '表示可' : '表示中';     
          $disp_status = ($data["trans_flg"] == '0') ? \Config::get('constant.performance_disp_status.browse') : \Config::get('constant.performance_disp_status.public');
        break;
        //販売可
        case \Config::get('constant.performance_status.sale') : 
          $now = strtotime("now");  
          if ($now >= strtotime($data['star_date']) && $data["trans_flg"] > 0) {
          //処理日 <= 販売開始日 且つ 連携フラグON    
            $disp_status = \Config::get('constant.performance_disp_status.saling');
            // if ($now >= strtotime($data["performance_st_dt"])) {
            if(Carbon::now()->gt(Carbon::parse($data["performance_st_dt"]." 00:00:00"))) {
              //処理日 >= 公演開始日
              // $status = '期間中';     
              $disp_status = \Config::get('constant.performance_disp_status.ongoing');
            }
            // if ($now >= strtotime($data["performance_end_dt"])) {
            if(Carbon::now()->gt(Carbon::parse($data["performance_end_dt"]." 23:59:59"))) {
              //処理日 >= 公演終了日
              // $status = '終了';  
              $disp_status = \Config::get('constant.performance_disp_status.close');
            }
          }
          else {
            $disp_status = \Config::get('constant.performance_disp_status.sale');
          }
        break;
  
        //unknow
        default :
          $disp_status = \Config::get('constant.performance_disp_status.unkonw');
        break;
      }

      if( $data['trans_flg'] > 0 && $data['sale_type'] == 0 && $disp_status > \Config::get('constant.performance_disp_status.sale')){
        $disp_status = \Config::get('constant.performance_disp_status.sale');
      }

      return $disp_status;
    }
    /**
     * 取得活動
     * @param string $GLID
     * @return collections GeneralReservationModel
     */
    public function getPerformanceList($GLID){
        try {
            return EvenManageModel::select(
                                        'performance_id',
                                        'performance_name'
                                    )
                                    ->with([
                                        'schedule' => function ($query) {
                                            $query->select(
                                                'schedule_id',
                                                'performance_id',
                                                'performance_date',
                                                'start_time',
                                                'disp_performance_date'
                                            );
                                        }
                                    ])
                                    ->when($GLID, function ($query) use ($GLID) {
                                        return $query->where('GLID', $GLID);
                                    })
                                    ->where("status", ">", 2)
                                    ->orderBy('performance_id', 'desc')
                                    ->get();
            
         }catch (Exception $e) {
             Log::error('getPerformanceList :'.$e->getMessage());
             return false;
         }
     }
    /**
     * 取得訂單 - 訂單查詢
     * @param array $data
     * @return collections GeneralReservationModel
     */
    public function getAllOrders($data, $GLID){
        try{
            return GeneralReservationModel::with(
                                                'seatSale', 
                                                'cancelOrder',
                                                'amountRevise',
                                                'seatSale.schedule', 
                                                'seatSale.schedule.performance',
                                                'seatSale.seat', 
                                                'seatSale.seat.seatClass',
                                                'seatSale.seat.hallSeat',
                                                'seatSale.seat.hallSeat.floor',
                                                'seatSale.seat.hallSeat.block',
                                                'questionAnswer',
                                                'questionAnswer.questionLang.question'
                                            )
                                            ->reserveKeyword($data['keyword'])
                                            ->betweenRserveDate(date($data['dateRangeStar']), date($data['dateRangeEnd']))
                                            ->betweenPayMethod($data['pay_method'])
                                            ->betweenPickupMethod($data['pickup_method'])
                                            ->when($GLID, function ($query) use ($GLID) {
                                                return $query->where('GLID', $GLID);
                                            })
                                            ->orderBy('order_id', 'desc')
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->seatStatus($data['order_status']);
                                            })
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->SaleType($data['sale_type']);
                                            })
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->paymentFlg($data['receipt']);
                                            })
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->seatIssue($data['issue']);
                                            })
                                            ->whereHas('seatClass', function ($query) use ($data){
                                                $query->ofType($data['seat_class']);
                                            })
                                            ->whereHas('seatSale.schedule', function ($query) use ($data){
                                                $query->findSchedule($data['schedulesId']);
                                            })
                                            ->whereHas('seatSale.schedule.performance', function ($query) use ($data){
                                                $query->findPerformance($data['performanceId']);
                                            })
                                            ->get();
        }catch (Exception $e){
            Log::error('sellManageRepositories - getOrders :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0011'));
        }
    }
    /**
     * 取得訂單
     * @param array $data
     * @return collections GeneralReservationModel
     */
    public function getOrders($data, $GLID){
        try{
            return GeneralReservationModel::with([
                                                'seatSale', 
                                                'cancelOrder',
                                                'amountRevise',
                                                'seatSale.schedule',
                                                'seatSale.schedule.performance' => function ($query) {
                                                    $query->select(
                                                       'performance_id',
                                                       'status',
                                                       'paid_status',
                                                       'performance_name',
                                                       'performance_name_sub',
                                                       'sch_kbn',
                                                       'performance_st_dt',
                                                       'performance_end_dt',
                                                       'hall_code',
                                                       'hall_disp_name',
                                                       'seatmap_profile_cd',
                                                       'disp_start',
                                                       'disp_end'
                                                    );
                                                },
                                                'seatSale.seat', 
                                                'seatSale.seat.seatClass',
                                                'seatSale.seat.hallSeat',
                                                'seatSale.seat.hallSeat.floor',
                                                'seatSale.seat.hallSeat.block',
                                                'questionAnswer.questionLang.question'
                                            ])
                                            ->reserveKeyword($data['keyword'])
                                            ->betweenRserveDate(date($data['dateRangeStar']), date($data['dateRangeEnd']))
                                            ->betweenPayMethod($data['pay_method'])
                                            ->betweenPickupMethod($data['pickup_method'])
                                            ->when($GLID, function ($query) use ($GLID) {
                                                return $query->where('GLID', $GLID);
                                            })
                                            ->orderBy('order_id', 'desc')
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->seatStatus($data['order_status']);
                                            })
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->SaleType($data['sale_type']);
                                            })
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->paymentFlg($data['receipt']);
                                            })
                                            ->whereHas('seatSale', function ($query) use ($data){
                                                $query->seatIssue($data['issue']);
                                            })
                                            ->whereHas('seatClass', function ($query) use ($data){
                                                $query->ofType($data['seat_class']);
                                            })
                                            ->whereHas('seatSale.schedule', function ($query) use ($data){
                                                $query->findSchedule($data['schedulesId']);
                                            })
                                            ->whereHas('seatSale.schedule.performance', function ($query) use ($data){
                                                $query->findPerformance($data['performanceId']);
                                            })
                                            ->paginate(30);
        }catch (Exception $e){
            Log::error('sellManageRepositories - getOrders :'.$e->getMessage());
            throw new Exception (trans('error.S_EXC_MSN_0011'));
        }
    }
    public function getDraftData($draft_id){
       try {
            $draft = DraftModel::findOrFail($draft_id);
           
            return  $draft->toArray();
        }catch (Exception $e) {
            Log::error('getPerformance :'.$e->getMessage());
            return false;
        }
    }
    public function updateDraftData($data){
        try {
       
            $this->DraftModel  = DraftModel::findOrFail($data['draft_id']);
            $this->DraftModel->draft_info = $data['draft_info'];
            $saved =  $this->DraftModel->save();

            if(!$saved){
                return false;
            }else{
                return true;
            }
        }catch (Exception $e) {
            Log::error('updateDraftData :'.$e->getMessage());
            return false;
        }
     }
    public function getSeatNowStatus($alloc_seat_id, $scheduleId){
        // $now = date("Y-m-d H:i:s");
        // $add_time = strtotime("-15 minutes", strtotime($now)); 
        // $expire = date('Y-m-d H:i:s', $add_time); 

        // $time = array(
        //     'now' => $now,
        //     'expire' => $expire,
        // );

        $resutlt =  SeatSaleModel::leftJoin('GL_GENERAL_RESERVATION', function($join)
                                        {
                                            $join->on('GL_SEAT_SALE.order_id', '=', 'GL_GENERAL_RESERVATION.order_id')
                                                ->where('GL_GENERAL_RESERVATION.cancel_flg', '=', 0);
                                        }
                                    )
                                    ->where('alloc_seat_id', $alloc_seat_id)
                                    ->where('schedule_id', $scheduleId)
                                    ->where('seat_status', '>',0)
                                    // ->where(function ($query) use ($time){
                                    //     $query->whereIn('GL_SEAT_SALE.seat_status', [3])
                                    //             ->orWhere(function ($query) {
                                    //                 $query->where('GL_SEAT_SALE.payment_flg', '<>', 0)
                                    //                         ->where('GL_SEAT_SALE.seat_status', '>', 1);
                                    //             })
                                    //             ->orWhere(function ($query) use ($time) {
                                    //                 $query->where(function ($query) use ($time){
                                    //                         $query->where('GL_SEAT_SALE.seat_status', '=', 1)
                                    //                             ->where(function ($query) use ($time){
                                    //                                 $query->where(function ($query) use ($time) {
                                    //                                             $query->whereNotNull('GL_SEAT_SALE.order_id')
                                    //                                                   ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', $time['now']);
                                    //                                         })
                                    //                                         ->orWhere(function ($query) use ($time) {
                                    //                                             $query->whereNull('GL_SEAT_SALE.order_id')
                                    //                                                     ->where('GL_SEAT_SALE.temp_reserve_date', '>', $time['expire']);
                                    //                                         });
                                    //                             });      
                                    //                 });
                                    //             })
                                    //             ->orWhere(function ($query) use ($time) {
                                    //                 $query->where('GL_SEAT_SALE.seat_status', '=', 2)
                                    //                         ->whereNotNull('GL_SEAT_SALE.order_id')
                                    //                         ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', $time['now']);
                                    //             });
                                    //     })
                                        ->exists();

        if($resutlt){
            return false;
        }else{
            return true;
        }
    }
    public function getGeneralReservationInf($order_id){
        $resutlt =  $this->GeneralReservationModel->whereIn('order_id', $order_id)
                                                    ->with('cancelOrder', 'amountRevise', 'amountRevise.userAccount', 'seatSale', 'seatSale.seatClass', 'seatSale.seat', 'seatSale.seat.seatClass', 'seatSale.seat.stageSeats', 'seatSale.seat.hallSeat', 'seatSale.seat.hallSeat.floor', 'seatSale.seat.hallSeat.block', 'questionAnswer', 'questionAnswer.questionLangJa', 'questionAnswer.questionLangJa.question')
                                                    ->get();
        return  $resutlt->toArray();
    }

    public function getPerformance($performanceId){
        try {
            $this->EvenManageModel = EvenManageModel::findOrFail($performanceId);
            
            return true;
        }catch (Exception $e) {
            Log::error('getPerformance :'.$e->getMessage());
            return false;
        }
    }
    //2021-06-23 STS - TASK 24: Get date and time of performance --START
    /**
     * Get all schedules of published performance
    */
    public function getScheduleDateTimeInf(){
        $schedule = $this->EvenManageModel->schedule()->get();
        foreach($schedule as $item){
            $data[] = array(
                'schedule_id' => $item->schedule_id,
                'performance_date' => $item->performance_date,
                'start_time' => $item->start_time,
            );
        }
        return $data;
    }
    /**
     * Get all schedules of unpublished performance
    */
    public function getUnpublishDateTimeInf(){
        $draft = $this->EvenManageModel->draft()->first();
        $temporary_info    = json_decode($draft->draft_info);
        $timeSetting       = $temporary_info->timeSetting;
        foreach($timeSetting->calenderDate as $sch){
            $date_value = $sch->date->dateValue;
            if($sch->date->hadEvens){
                foreach($sch->date->rule as $idx => $sch){ 
                    $data[] = array(
                        'draft_id' => $draft['draft_id'],
                        'performance_date' => date("Y-m-d", strtotime($sch->date)),      
                        'date_value' => $date_value,
                        'start_time' => $sch->time,    
                        'rule_id' => $sch->id,
                    );                                             
                }    
            }   
        }
        return $data;
    }              
    //2021-06-24 STS - TASK 24 --END
    public function getPerformanceSellInf(){
        try {
            $tt = json_decode($this->EvenManageModel->temporary_info);
            $temporary_info = isset($tt->basisData) ? $tt->basisData : "";
      
            //販売期間
            $earlyBirdDateStart = isset($temporary_info->earlyBirdDateStart) ? $temporary_info->earlyBirdDateStart : ""; //先行from
            $normalDateStart    = isset($temporary_info->normalDateStart) ? $temporary_info->normalDateStart : "";    //一般from
            if (!empty($normalDateStart)) $starDate = $normalDateStart;
            if (!empty($earlyBirdDateStart)) $starDate = $earlyBirdDateStart;

            $status_data = array(
                'status'    => $this->EvenManageModel->status,
                'star_date' => $starDate,
                'performance_st_dt' => $this->EvenManageModel->performance_st_dt,
                'performance_end_dt' =>  $this->EvenManageModel->performance_end_dt,
                'sale_type' => $this->EvenManageModel->trans_flg,
                'trans_flg' => $this->EvenManageModel->sale_type,
            );

            $disp_status = $this->getPerformanceDispStatus($status_data);

            $data = array(
                'performance_id' => $this->EvenManageModel->performance_id,
                'disp_status' => $disp_status,                        
                'performance_name' => $this->EvenManageModel->performance_name,                    
                'performance_name_sub' => $this->EvenManageModel->performance_name_sub,        
                'sch_kbn' => $this->EvenManageModel->sch_kbn ,           
                'performance_st_dt' => $this->EvenManageModel->performance_st_dt,                        
                'performance_end_dt' => $this->EvenManageModel->performance_end_dt, 
                'seatmap_profile_cd' => $this->EvenManageModel->seatmap_profile_cd,  
                'trans_flg' => $this->EvenManageModel->trans_flg,  
            );
            
            return $data;
        }catch (Exception $e) {
            Log::error('getPerformanceSellInf :'.$e->getMessage());
            return false;
        }

    }

    //STS 2020/08/06 Task 25 - START FIX
    public function getScheduleSeatData($performanceId,$scheduleId ){
        $reserveSeatData = $this->getScheduleSelect_RESSeatData($performanceId, $scheduleId);
        $reserveSeat = array();
        //Get all Seat_class
        $seatData = $this->getSeatTypeData($performanceId);
        $sch_kbn = $this->getPerformanceData($performanceId)->sch_kbn;
        // $selectData = $this->getSelectSeatData($performanceId);      
        foreach($seatData as $key => $value){  
            if($value->seat_class_kbn == 1)
            {    
                $seat_class_id = $value->seat_class_id;
                $total_seat = 0;
                $total_seat_reserved = $this->getTypeSeatData($scheduleId,$seat_class_id, 2);
                $total_seat_sold = 0;
                $seat_total_price = 0;
                foreach($reserveSeatData as $key => $data){
                    if($data->stage_seat_id){
                        if($data->stage_seat_class_id == $seat_class_id){
                            $total_seat++;
                            if(!is_null($data->order_id)){
                                $total_seat_sold++;
                                $seat_total_price+=$data->sale_price;
                            }
                        }
                    }else{
                        if($data->seat_class_id == $seat_class_id){
                            $total_seat++;
                            if(!is_null($data->order_id)){
                                $total_seat_sold++;
                                $seat_total_price+=$data->sale_price;
                            }
                        }
                    }
                }
                $reserveSeat[] = array(
                    'reserve_code' => $value->seat_class_id,
                    'reserve_name' => $value->seat_class_name,
                    'total_seat' => $total_seat,
                    'total_Seat_Reserved' => $total_seat_reserved,
                    'total_Seat_Sold' => $total_seat_sold,
                    'total_Seat_Press' => 0,
                    'total_price' => $seat_total_price,
                );
            }
            else
            {
                //get all details of FreeSeat 
                $total_seat = $this->getNonreservedStockLimit($scheduleId,$value->seat_class_id)['stock_limit'];
                $total_seat_reserved = $this->getTypeSeatData($scheduleId,$value->seat_class_id, 2);
                $total_seat_sold = $this->getTypeSeatData($scheduleId,$value->seat_class_id, 3);
                $seat_total_price = $this->getPriceSeatData($scheduleId,$value->seat_class_id);;
                if($total_seat == 0 && $sch_kbn == 0) $total_seat =  trans('common.S_Unlimited');
                $reserveSeat[] = array(
                    'reserve_code' => $value->seat_class_id,
                    'reserve_name' => $value->seat_class_name,
                    'total_seat' => $total_seat,
                    'total_Seat_Reserved' => $total_seat_reserved,
                    'total_Seat_Sold' => $total_seat_sold,
                    'total_Seat_Press' => 0,
                    'total_price' => $seat_total_price,
                );
            }
        }
        //get all details of Reserve Seat
        $reserveData = $this->getReservationData($performanceId);       
        foreach($reserveData as $key => $value){
            $reserveCode = $value->reserve_code;
            $total_seat = 0;
            $total_seat_reserved = $this->getTypeSeatData($scheduleId,$seat_class_id, 2);
            $total_seat_sold = 0;
            $seat_total_price = 0;
            foreach($reserveSeatData as $key => $data){
                if($data->stage_seat_id){
                    if($data->stage_reserve_code == $reserveCode){
                        $total_seat++;
                        if(!is_null($data->order_id)){
                            $total_seat_sold++;
                            $seat_total_price+=$data->sale_price;
                        }
                    }
                }else{
                    if($data->reserve_code == $reserveCode){
                        $total_seat++;
                        if(!is_null($data->order_id)){
                            $total_seat_sold++;
                            $seat_total_price+=$data->sale_price;
                        }
                    }
                }
            }
            $reserveSeat[] = array(
                'reserve_code' => $value->reserve_code,
                'reserve_name' => $value->reserve_name,
                'total_seat' => $total_seat,
                'total_Seat_Reserved' => 0,
                'total_Seat_Sold' => $total_seat_sold,
                'total_Seat_Press' => $total_seat,
                'total_price' => $seat_total_price,
            );
        }
        
        return $reserveSeat;
    }
    public function getTypeSeatData($schedule_id, $seat_class_id, $seat_status){
        $result = $this->SeatSaleModel->select(
                                            'GL_SEAT_SALE.seat_sale_id'
                                        )
                                        ->where('schedule_id', $schedule_id)
                                        ->where('seat_class_id', $seat_class_id)
                                        ->where('seat_status', $seat_status)
                                        ->count();
        return $result;
    }
    
    //STS task 25 30/07/2021 start
    public function getPriceSeatData($schedule_id, $seat_class_id){
        $result = $this->SeatSaleModel->select(
                                            'GL_SEAT_SALE.sale_price'
                                        )
                                        ->where('schedule_id', $schedule_id)
                                        ->where('seat_class_id', $seat_class_id)
                                        ->where('payment_flg', 1) 
                                        ->where('seat_status',3) //STS task 25 2021/08/10
                                        ->where('sale_type',0) //STS task 25 2021/08/10
                                        ->sum('sale_price'); //STS task 25 2021/08/02
        return $result;
    }
    //STS task 25 30/07/2021 end

    public function getCountSelectSeat( $seat_class_id, $performance_id ){
        $result = $this->SeatModel->select(
                                            'GL_SEAT.seat_id'
                                        )
                                        ->where('seat_class_id', $seat_class_id)
                                        ->where('performance_id', $performance_id) //STS 2021/08/02 Task 25
                                        ->count();
        return $result;
    }
    public function getNonreservedStockLimit($schedule_id, $seat_class_id){
        $result = $this->NonreservedStockModal->where('schedule_id', $schedule_id)
        ->where('seat_class_id', $seat_class_id)
        ->get();
        return $result->toArray()[0];
    }
    //STS task 25 2020/06/24 end

    public function getScheduleSellInf($performanceId){   //STS task 25 2020/06/24
       
       if(!is_null($this->EvenManageModel->announce_date)){
            $schedule = $this->EvenManageModel->schedule()->get();
            //$GLVSeatStaticOfStage_data =  $this->EvenManageModel->GLVSeatStaticOfStage()->get()->keyBy('schedule_id');;
            //$GLVStatsOfStage_data =  $this->EvenManageModel->GLVStatsOfStage()->get()->keyBy('schedule_id');;
                
            foreach($schedule as $item){
                $ReservationSeatData = $this->getScheduleSeatData($performanceId,$item->schedule_id); //STS task 25 2020/06/24
                // $GL_V_Seat_Static_of_Stage = $GLVSeatStaticOfStage_data[$item->schedule_id];
                //$GL_V_stats_of_stage = $GLVStatsOfStage_data[$item->schedule_id];
                $stage_name = $item->Stagename->stage_name;
                // $check = $item->sch_kbn;  //STS task 25 2020/07/21
                // if(empty($stage_name)&&$check==0)$stage_name = $item->disp_performance_date; //STS task 25 30/07/2021
                // $seatTypeData = $this->getSeatTypeData($item->performance_id);//STS task 25 2020/06/24
                //STS task 25 2020/06/24 start
                $seatData = array();
               
                // foreach ($seatTypeData as $seatType ){
                //     $seatClassKbn = $seatType->seat_class_kbn;
                //     $seatName = $seatType->seat_class_name ;
                //     $seatTotal = $seatClassKbn == 1 ? $this->getCountSelectSeat($seatType->seat_class_id, $performanceId) : $this->getNonreservedStockLimit($item->schedule_id,$seatType->seat_class_id )['stock_limit']; //STS task 25 2020/07/21
                //     $seatReservation = $this->getTypeSeatData($item->schedule_id,$seatType->seat_class_id, 2);
                //     $seatSoild = $this->getTypeSeatData($item->schedule_id,$seatType->seat_class_id, 3 );
                //     $seatRes = $GL_V_stats_of_stage->cnt_rev_issue;
                //     $seatPrice = $this->getPriceSeatData($item->schedule_id,$seatType->seat_class_id);//STS task 25 30/07/2021
                //     $seatData[] = array(
                //         'seat_name' => $seatName?$seatName:0,
                //         'seat_total' => ($GL_V_Seat_Static_of_Stage->stock_limit=='0'&&$check==0)?'無制限':$seatTotal, //STS task 25 2020/07/21 start
                //         'seat_reservation' => $seatReservation?$seatReservation:0,
                //         'seat_sold' => $seatSoild?$seatSoild:0,
                //         'seat_res' => $seatRes?$seatRes:0,
                //         'seat_price' => $seatPrice?intval($seatPrice):0, //STS task 25 30/07/2021
                //     );
                // }
                // $total_seats = 0;
                // $total_reserved = 0;
                // $total_sold = 0;
                // $total_res = 0;
                // $subtotal = 0;
                foreach($ReservationSeatData as $seat){
                    // $total_seats +=intval($seat['total_seat']);
                    // $total_reserved += intval($seat['total_Seat_Reserved']);
                    // $total_sold += intval($seat['total_Seat_Sold']);
                    // $total_res += intval($seat['total_Seat_Press']);
                    // $subtotal += intval($seat['total_price']);
                    $seatData[] = array(
                        'seat_name' => $seat['reserve_name'],
                        'seat_total' => $seat['total_seat'],
                        'seat_reservation' => $seat['total_Seat_Reserved'],
                        'seat_sold' => $seat['total_Seat_Sold'],
                        'seat_res' => $seat['total_Seat_Press'],
                        'seat_price' => intval($seat['total_price']), //STS task 25 30/07/2021
                    );
                }

                //STS task 25 2020/06/24 end

                $data[] = array(
                    'schedule_id' => $item->schedule_id,
                    'performance_date' => $item->performance_date,      
                    'start_time' => $item->start_time,          
                    'cancel_flg' => $item->cancel_flg,                 
                    // 'RES' => $GL_V_Seat_Static_of_Stage->RES,                        
                    // 'SALE' => $GL_V_Seat_Static_of_Stage->SALE,
                    // 'UNSET' => $GL_V_Seat_Static_of_Stage->UNSET,                        
                    // 'stock_limit' => $GL_V_Seat_Static_of_Stage->stock_limit,
                    // 's_schedule_id' => $GL_V_stats_of_stage->s_schedule_id,
                    // 'cnt_inpay_rev' => $GL_V_stats_of_stage->cnt_inpay_rev,                        
                    // 'cnt_inpay_free' => $GL_V_stats_of_stage->cnt_inpay_free,                   
                    // 'cnt_sale_rev' => $GL_V_stats_of_stage->cnt_sale_rev,        
                    // 'cnt_sale_free' => $GL_V_stats_of_stage->cnt_sale_free ,             
                    // 'cnt_rev_issue' => $GL_V_stats_of_stage->cnt_rev_issue,                       
                    // 'subtotal' => $GL_V_stats_of_stage->subtotal, 
                    // 'total_seats' => $total_seats,
                    // 'total_reserved' => $total_reserved,
                    // 'total_sold' => $total_sold,
                    // 'total_res' => $total_res,
                    // 'subtotal' => $subtotal,
                    'stage_name' => $stage_name,
                    //STS task 25 2020/06/24 start
                    'seat_Data_First' => array_shift($seatData),//seatData[0]
                    'seat_Data' => $seatData,//seatData[1,2,3...]
                    //STS task 25 2020/06/24 end
                    
                );
            
            }
       }else{
           $draft = $this->EvenManageModel->draft()->first();

            //公演ステータス編集中
            //座席数、押え数をtemporary_infoより取得
            $temporary_info    = json_decode($draft->draft_info);
            $basisData         = $temporary_info->basisData;
            $timeSetting       = $temporary_info->timeSetting;                     //スケジュール
            $ticketSetting     = $temporary_info->ticketSeeting->ticketSetting;    //席種・券種
            $specTicketSetting = $temporary_info->ticketSeeting->specTicketSetting;//押え   
            $mapData           = $temporary_info->mapData[0];

            $free_sum = 0;
            $free_u = false;
            $free_had = false;
            $seat_sum = 0;
            $schedule_sum = 0;
            $seat_res = 0;
            $respective_free_data = [];

            if($timeSetting->status[0]->status == 'normal'){
                        
                //場次 自由
                if($ticketSetting->settingType == 'freeSeat'){
                    // $seat_free = $ticketSetting->seatQty;
                    $temp_arry = [];
                    $ticketSetting->data->seatTotal = $ticketSetting->data->seatQty;
                    $ticketSetting->data->seatFree = true;
                    array_push($temp_arry, $ticketSetting->data);
                    $ticketSetting->data = $temp_arry;
                }
                
                //場次 自由
                if($ticketSetting->settingType == 'selectSeat' || $ticketSetting->settingType == 'freeSeat'){
                    $seat_free = $ticketSetting->seatQty;

                    foreach($timeSetting->calenderDate as $calenderDate)
                    {
                        $schedule_sum = $schedule_sum + count($calenderDate->date->rule); 
                    } 
                   
                    //席位計算
                    foreach($ticketSetting->data as $set_seat){
                        if($set_seat->seatFree){
                            if($set_seat->seatTotal == 0){
                                $free_u = true;
                            }
                            $free_had = true;
                            $free_sum += $set_seat->seatTotal;

                            if(isset($set_seat->respectiveData)){
                                foreach($set_seat->respectiveData as $value){
                                      if(!isset($respective_free_data[$value->dateValue][$value->ruleId])){
                                        $respective_free_data[$value->dateValue][$value->ruleId] = 0;
                                      } 

                                      $respective_free_data[$value->dateValue][$value->ruleId] += ((int)$value->total - $set_seat->seatTotal);
                                }
                            }
                        }

                        if(!$set_seat->seatFree){
                            $seat_sum += ($set_seat->seatTotal);
                        }
                    }
                   
                    $seat_sale = $seat_sum;
                    
                    if(!$free_had){
                        $seat_free = '';
                    }else if($free_u && $timeSetting->status[0]->status == 'normal'){
                        $seat_free = 0 ;
                    }else{
                        $seat_free = $free_sum ;
                    }
                    if(isset($specTicketSetting->data)){
                        foreach($specTicketSetting->data as $res_data){
                            $seat_res += $res_data->ticketTotal;
                        }
                    }
                }
            }else{
                $seat_free = $ticketSetting->seatQty;
            }
        
                    //スケジュール
            $stage = 0;

            if($timeSetting->status[0]->status == 'spec'){
                //特定スケジュール無し
                //活動期間のfrom-toより公演日数取得
                $from = strtotime($basisData->performance_st_dt);
                $to   = strtotime($basisData->performance_end_dt);
                $stage = ($to - $from) / (60 * 60 * 24);
                $stage++;  
                //開始時間
                $start_time = $timeSetting->specDate[0]->specDate;
                //ステージ
                $stage_name = $timeSetting->specDate[0]->specTitle;
                //開催日 公演開始日-公演終了日
                $performance_date = $basisData->performance_st_dt . ' - ' . $basisData->performance_end_dt; 
                $free_sum = 0;
                $free_u = false;
                $free_had = false;
                $seat_sum = 0;

                //STS task 25 2020/06/24 start
                $draftData = json_decode($this->getDraftData($draft['draft_id'])['draft_info']);
                $seatData[] = array(
                    'seat_name' => $draftData->ticketSeeting->ticketSetting->data->seatName,
                    'seat_total' => $draftData->ticketSeeting->ticketSetting->data->seatQty>0?$draftData->ticketSeeting->ticketSetting->data->seatQty:trans('common.S_Unlimited'), //STS task 25 2020/07/21
                    'seat_reservation' => 0,
                    'seat_sold' => 0,
                    'seat_res' => 0,
                    'seat_price' => 0, //STS task 25 30/07/2021
                );
                //STS task 25 2020/06/24 end

                $data[] = array(
                            'draft_id' => $draft['draft_id'],
                            'schedule_id' => '',
                            'time_setting' => $timeSetting->status[0]->status,
                            'performance_date' => $performance_date,      
                            'start_time' => $start_time,          
                            'cancel_flg' => 0,                 
                            // 'RES' => $seat_res,                        
                            // 'SALE' => $seat_sum,
                            // 'UNSET' => $free_sum,                        
                            // 'stock_limit' => $seat_free,
                            // 's_schedule_id' => '',
                            // 'cnt_inpay_rev' => 0,                        
                            // 'cnt_inpay_free' => 0,                   
                            // 'cnt_sale_rev' => 0,        
                            // 'cnt_sale_free' => 0,             
                            // 'cnt_rev_issue' => 0,                       
                            // 'subtotal' => 0, 
                            'stage_name' => $this->EvenManageModel->sch_kbn=0?$stage_name:'',  //STS task 25 2021/08/02
                            //STS task 25 2020/06/24 start
                            'seat_Data_First' => array_shift($seatData),//seatData[0]
                            'seat_Data' => $seatData,//seatData[1,2,3...]
                            //STS task 25 2020/06/24 end
                        );

            }else if($timeSetting->status[0]->status == 'normal'){   
                $seat_type_count = array(
                    'RES' => $seat_res,                        
                    'SALE' => $seat_sum,
                    'UNSET' => $free_sum,
                );
               
                 $respective_data = $this->getRespectiveData($mapData, $seat_type_count);

                //特定スケジュール有り
                $dateArr = array(); 
                foreach($timeSetting->calenderDate as $sch){
                    $date_value = $sch->date->dateValue;
                    if($sch->date->hadEvens){
                        foreach($sch->date->rule as $idx => $sch){ 
                            if(!in_array($sch->status, array('N', 'U', 'I'))){
                               continue;
                            }
                            if(isset($respective_data[$date_value][$sch->id])){
                                $res = $respective_data[$date_value][$sch->id]['RES'];
                                $sale = $respective_data[$date_value][$sch->id]['SALE'];
                                $unset = $respective_data[$date_value][$sch->id]['UNSET'];
                            }else{
                                $res = $seat_type_count['RES'];
                                $sale = $seat_type_count['SALE'];
                                $unset = $seat_type_count['UNSET'];
                            }

                            if(isset($respective_free_data[$date_value][$sch->id])){
                                $seat_free_sum = $seat_free + $respective_free_data[$date_value][$sch->id];
                            }else{
                                $seat_free_sum = $seat_free;
                            }

                             //STS task 25 2021/06/24 start
                             $draftData = json_decode($this->getDraftData($draft['draft_id'])['draft_info']);
                             //STS task 25 2021/06/24 start updated 2021/08/06
                             $seatData = array();
                             $type = $draftData->ticketSeeting->ticketSetting->settingType;
                             if($type == "freeSeat"){
                                 $seatData[] = array(
                                     'seat_name' => $draftData->ticketSeeting->ticketSetting->data->seatName,
                                     'seat_total' => $seat_free_sum,
                                     'seat_reservation' => 0,
                                     'seat_sold' => 0,
                                     'seat_res' => 0,
                                     'seat_price' => 0
                                 );
                             }else{
                                $typeSeatDatas = $draftData->ticketSeeting->ticketSetting->data;
                                $custom=array(); // Data mẫu để đếm số lượng ghế
                                $index = 0; // Thứ tự các ghế
                                $hasSelectSeat = false;
                                foreach($typeSeatDatas as $seat){  
                                    if(!isset($seat->seatStatus) || !in_array($seat->seatStatus, array('N', 'U', 'I'))){
                                         continue;
                                    }
                                    
                                    if(!$seat->seatFree){
                                        $hasSelectSeat = true;
                                        $custom['select'][$index] = array(
                                            'seatName'  => $seat->seatName,
                                            'seatTotal' => $seat->seatTotal,
                                            'seatid'    => $seat->seatid,
                                            'seatColor' => $seat->seatColor
                                        ); 
                                    }else{
                                        $seatName   = $seat->seatName;
                                        $seatTotal  = $seat->seatTotal;
                                        
                                        if(isset($seat->respectiveData) && !empty($seat->respectiveData)){ 
                                            foreach($seat->respectiveData as $freeSeat){
                                                if ($freeSeat->dateValue == $date_value && $freeSeat->ruleId ==  $sch->id ){
                                                    $seatTotal = $freeSeat->total;
                                                }
                                            }
                                        }  
                                        $seatData[$index] = array(
                                            'seat_name' => $seatName,
                                            'seat_total' => $seatTotal,
                                            'seat_reservation' => 0,
                                            'seat_sold' => 0,
                                            'seat_res' => 0,
                                            'seat_price' => 0
                                        );
                                    }
                                    $index++;
                                
                                };

                                if($hasSelectSeat){
                                    if(!isset($custom['select'])){
                                        $custom['select'][$index] = array(); 
                                    };
                                    $specSeat = isset($draftData->ticketSeeting->specTicketSetting->data) ? $draftData->ticketSeeting->specTicketSetting->data : [];
                                    $custom['spec']        = $specSeat;
                                    $custom['date_value']  = $date_value;
                                    $custom['ruleId']    = $sch->id;
                                    $respective_data2 = $this->getRespectiveData2($draftData->mapData[0], $custom);//task 25 07/08/2021                                 
                                    
                                    
                                    $freeSeatArr = $seatData; // Ghế k chọn chổ
                                    $selectSeatArr = array_slice($respective_data2, 0,-count($specSeat), TRUE); //Ghế chọn chổ
                                    $specSeatArr = array_slice($respective_data2, count($selectSeatArr)); // Ghế đặc biệt
                                    $seatData = $freeSeatArr + $selectSeatArr; // Tổng các ghế - đặc biệt
                                    ksort($seatData); // Sắp xếp thứ tự
                                    $seatData = array_merge($seatData , $specSeatArr); //Tổng ghế gồm ghế đặc biệt
                                }
                                 
                                }
                              

                            $data[] = array(
                                'draft_id' => $draft['draft_id'],
                                'schedule_id' => '',
                                'time_setting' => $timeSetting->status[0]->status,
                                'performance_date' => date("Y-m-d", strtotime($sch->date)),      
                                'date_value' => $date_value,
                                'start_time' => $sch->time,    
                                'rule_id' => $sch->id,
                                'cancel_flg' => 0,                 
                                // 'RES' => $res,                        
                                // 'SALE' => $sale,
                                // 'UNSET' => $unset,                        
                                // 'stock_limit' => $seat_free_sum,
                                // 's_schedule_id' => '',
                                // 'cnt_inpay_rev' => 0,                        
                                // 'cnt_inpay_free' => 0,                   
                                // 'cnt_sale_rev' => 0,        
                                // 'cnt_sale_free' => 0,             
                                // 'cnt_rev_issue' => 0,                       
                                // 'subtotal' => 0, 
                                'stage_name' => $timeSetting->ruleList[$idx]->title,
                                //STS task 25 2020/06/24 start
                                'seat_Data_First' => array_shift($seatData),//seatData[0]
                                'seat_Data' => $seatData,//seatData[1,2,3...]
                                //STS task 25 2020/06/24 start
                            );  
                             //STS task 25 2021/06/24 end updated 2021/08/06
                        }    
                    }   
                }
            }          
        } 

        return $data;
    }

    // STS task 25 07/08/2021 start
    private function getRespectiveData2($mapData, $custom)
    {
        $respective_data = array();
        $dataId = array();
        foreach($mapData->data[0]->mapData as $block_data){
            foreach($block_data->blockData as $seat_data){
                foreach($seat_data->seatData as $seat_inf){ 
                    if(isset($seat_inf->respectiveData)){
                        foreach($seat_inf->respectiveData as $respective_inf){ 
                            $date_value = $respective_inf->dateValue;
                            $rule_id = $respective_inf->ruleId;
                            $index = $respective_inf->index;
                            if($date_value == $custom['date_value'] && $rule_id == $custom['ruleId']){
                                if(isset($seat_inf->typeData) && gettype($seat_inf->typeData) === 'object' && $seat_inf->typeData->id !== ""){
                                    switch($seat_inf->typeData->type){
                                        case 'specSeat':{ 
                                            switch($respective_inf->type){
                                                case 0:{
                                                    $tempSeatChanged[] = $seat_inf->typeData;                           
                                                    break;
                                                }
                                                case 1:{
                                                    $tempSeatChanged[] = $seat_inf->typeData;
                                                    $custom['spec'][$index]->ticketTotal ++;
                                             
                                                    break;
                                                }
                                                case 2:{// vé đặc biệt - vé thường
                                                
                                                    $tempSeatChanged[] = $seat_inf->typeData;
                                                    $custom['select'][$index]['seatTotal'] ++; 
                          
                                                    break;
                                                }
                                            }
                                            break;
                                        }
                                        case 'ticketSetting':{
                                            switch ($respective_inf->type) {
                                                case 0:{
                                                    $tempSeatChanged[] = $seat_inf->typeData;
                                                    break;
                                                }
                                                case 1:{//vé db
                                                    $custom['spec'][$index]->ticketTotal ++;
                                                    $tempSeatChanged[] = $seat_inf->typeData;
                                                 
                                                    break;
                                                }
                                                case 2:// vé thường
                                                    $custom['select'][$index]['seatTotal'] ++;
                                                    $tempSeatChanged[] = $seat_inf->typeData;
                                                    break;
                                            }
                                            break;
                                        }
                                    }
                                }else{
                                    switch($respective_inf->type) {
                                        case 1:{
                                            $custom['spec'][$index]->ticketTotal++;
                                            break;
                                        }
                                        case 2:{
                                            $custom['select'][$index]['seatTotal']++;
                                            break;
                                        }
                                    }
                                }
                            }
                        
                   
               
                        }
                      
                    }else{

                    }
                }

            }
        }
        
        foreach($custom['select'] as $key =>$seat){ //Lấy data ghế chọn chổ
            if (isset($tempSeatChanged))
            foreach($tempSeatChanged as $tempSeat){
                $seatColor = $seat['seatColor'];
                if($tempSeat->color === $seatColor){
                    $custom['select'][$key]['seatTotal']--;
                }
            };
            if(!empty($seat))
            $seatData[$key] = array(
                'seat_name' => $seat['seatName'],
                'seat_total' => $custom['select'][$key]['seatTotal'],
                'seat_reservation' => 0,
                'seat_sold' => 0,
                'seat_res' => 0,
                'seat_price' => 0
            );
        };

        foreach($custom['spec'] as $key =>$seat){ //Lấy data ghế đặc biệt
            if (isset($tempSeatChanged))
            foreach($tempSeatChanged as $tempSeat){
                $seatColor = $seat->ticketColor;
                if($tempSeat->color === $seatColor){
                    $custom['spec'][$key]->ticketTotal--;
                }
            }
            if(!empty($seat))
            $seatData[] = array(
                'seat_name' => $seat->ticketName,
                'seat_total' => $custom['spec'][$key]->ticketTotal,
                'seat_reservation' => 0,
                'seat_sold' => 0,
                'seat_res' => $custom['spec'][$key]->ticketTotal,
                'seat_price' => 0
            );

        };

        return $seatData;
    }
    //STS task 25 07/08/2021 end

    /**
     * 取得活動販賣資料
     * @param bool
     */
    public function getPerformationSellInf($filter_data){
        try {
           $performance_inf = [];
           $keyword = $filter_data['keyword'];
           $GLID = $filter_data['GLID'];
           
           $performance_list = EvenManageModel::whereNotIn('status', [0, 1, 2])
                                                    ->where('status', '>',0)
                                                    ->when($GLID, function ($query) use ($GLID) {
                                                        return $query->where('GLID', $GLID);
                                                    })
                                                    ->when($keyword, function ($query) use ($keyword) {
                                                        return $query->where(function ($query)  use ($keyword) {
                                                            $query->where('performance_code', 'like', "%{$keyword}%")
                                                            ->orWhere('performance_name', 'like', "%{$keyword}%")
                                                            ->orWhere('performance_name_k', 'like', "%{$keyword}%")
                                                            ->orWhere('performance_name_sub', 'like', "%{$keyword}%")
                                                            ->orWhere('performance_name_seven', 'like', "%{$keyword}%");
                                                        });
                                                    })
                                                    ->orderBy('performance_id', 'desc')->get();

            $performance_list =  $performance_list->load('GLVStatsOfPerformace', 'GLVSeatStaticOfStage', 'draft', 'salesTerm')->toArray();
           
            foreach($performance_list as $item){
                // if(!empty($item['draft'])){
                   
                    if($item['trans_flg'] == 0 && !empty($item['draft']) ){
                        $josn =  $item['draft']['draft_info'];
                    }else{
                        $josn = $item['temporary_info'];
                    }

                    $tt = json_decode($josn);
                    $temporary_info = isset($tt->basisData) ? $tt->basisData : "";
                    $mapData        = $tt->mapData[0];
                
                    //販売期間
                    $earlyBirdDateStart = isset($temporary_info->earlyBirdDateStart) ? $temporary_info->earlyBirdDateStart : ""; //先行from
                    $normalDateStart    = isset($temporary_info->normalDateStart) ? $temporary_info->normalDateStart : "";    //一般from
                    if (!empty($normalDateStart)) $starDate = $normalDateStart;
                    if (!empty($earlyBirdDateStart)) $starDate = $earlyBirdDateStart;
                
                    $status_data = array(
                        'status'    => $item['status'],
                        'star_date' => $starDate,
                        'performance_st_dt' => $item['performance_st_dt'],
                        'performance_end_dt' =>  $item['performance_end_dt'],
                        'sale_type' => $item['sale_type'],
                        'trans_flg' => $item['trans_flg'],
                    );

                    $disp_status = $this->getPerformanceDispStatus($status_data);
                   
                    if(in_array($disp_status, $filter_data['filter_status'])){
                       
                        if($item['trans_flg']  && !empty ($item['g_l_v_stats_of_performace']) && !empty($item['g_l_v_seat_static_of_stage'])){
                            $GL_V_Stats_of_performace = collect($item['g_l_v_stats_of_performace']);
                            $GL_V_seat_static_of_stage = collect($item['g_l_v_seat_static_of_stage']);
                        
                            if(is_null($GL_V_seat_static_of_stage[0]['stock_limit'])){
                                $stock_limit = '-';
                            }else {
                                $stock_limit_unlimit = $GL_V_seat_static_of_stage->where('stock_limit', 0)->count();
                                if($stock_limit_unlimit > 0 && $item['sch_kbn'] != 1){
                                    $stock_limit = 0;
                                }else{
                                    $stock_limit = $GL_V_seat_static_of_stage->sum('stock_limit');
                                }
                            }

                            $performance_inf[] = array(
                                'performance_id'        => $item['performance_id'],
                                'performance_name'      => $item['performance_name'],
                                'disp_status'           => $disp_status,
                                'trans_flg'             => $item['trans_flg'],
                                'sale_type'             => $item['sale_type'],
                                'RES'                   => $GL_V_seat_static_of_stage->sum('RES'),
                                'SALE'                  => $GL_V_seat_static_of_stage->sum('SALE'),
                                'UNSET'                 => $GL_V_seat_static_of_stage->sum('UNSET'),
                                'stock_limit'           => $stock_limit,
                                's_pid'                 => $GL_V_Stats_of_performace[0]['s_pid'],
                                'cnt_inpay_rev'         => $GL_V_Stats_of_performace[0]['cnt_inpay_rev'],                  
                                'cnt_inpay_free'        => $GL_V_Stats_of_performace[0]['cnt_inpay_free'],                  
                                'cnt_sale_rev'          => $GL_V_Stats_of_performace[0]['cnt_sale_rev'],              
                                'cnt_sale_free'         => $GL_V_Stats_of_performace[0]['cnt_sale_free'], 
                                'cnt_rev_issue'         => $GL_V_Stats_of_performace[0]['cnt_rev_issue'],                    
                                'subtotal'              => $GL_V_Stats_of_performace[0]['subtotal'],             
                                'sch_kbn'               => $item['sch_kbn'],
                            );
                        }else{
                            $seat_sale =  0;
                            $seat_res = 0;
                            $seat_un = 0;
                            $seat_free = '';
                            $respective_free_data = [];

                            if($tt->timeSetting->status[0]->status == 'normal'){
                                
                                //場次 自由
                                if($tt->ticketSeeting->ticketSetting->settingType == 'freeSeat'){
                                    // $seat_free = $tt->ticketSeeting->ticketSetting->seatQty;
                                    $temp_arry = [];
                                    $tt->ticketSeeting->ticketSetting->data->seatTotal = $tt->ticketSeeting->ticketSetting->seatQty;
                                    $tt->ticketSeeting->ticketSetting->data->seatFree = true;
                                    array_push($temp_arry, $tt->ticketSeeting->ticketSetting->data);
                                    $tt->ticketSeeting->ticketSetting->data = $temp_arry;                
                                }
                                
                                //場次 自由
                                if($tt->ticketSeeting->ticketSetting->settingType == 'selectSeat' || $tt->ticketSeeting->ticketSetting->settingType == 'freeSeat'){
                                    $seat_free = $tt->ticketSeeting->ticketSetting->seatQty;

                                    $free_sum = 0;
                                    $free_u = false;
                                    $free_had = false;
                                    $seat_sum = 0;
                                    $schedule_sum = 0;
                                    
                                    foreach($tt->timeSetting->calenderDate as $calenderDate)
                                    {
                                        $schedule_sum = $schedule_sum + count($calenderDate->date->rule); 
                                    } 
                                
                                    foreach($tt->ticketSeeting->ticketSetting->data as $set_seat){
                                        
                                        if($set_seat->seatFree){
                                            if($set_seat->seatTotal == 0){
                                                $free_u = true;
                                            }
                                            $free_had = true;
                                            $free_sum += $set_seat->seatTotal;

                                            if(isset($set_seat->respectiveData)){
                                                foreach($set_seat->respectiveData as $value){
                                                      if(!isset($respective_free_data[$value->dateValue][$value->ruleId])){
                                                        $respective_free_data[$value->dateValue][$value->ruleId] = 0;
                                                      } 
                                                      $respective_free_data[$value->dateValue][$value->ruleId] += ((int)$value->total - $set_seat->seatTotal);
                                                }
                                            }
                                        }

                                        if(!$set_seat->seatFree){
                                            $seat_sum += $set_seat->seatTotal; //* $schedule_sum);
                                        }
                                    }
                                    $seat_sale = $seat_sum;
                                    
                                    if(!$free_had){
                                        $seat_free = '';
                                    }else if($free_u && $item['sch_kbn'] != 1){
                                        $seat_free = 0 ;
                                    }else{
                                        $respective_total = 0;
                                        $seat_free_resp = 0;
                                        foreach($respective_free_data as $respective){
                                            foreach($respective as $value){
                                                $respective_total ++;
                                                $seat_free_resp += $value;
                                            }
                                        }
                                        
                                        $seat_free = $free_sum * $schedule_sum + $seat_free_resp;
                                    }
                                    if(isset($tt->ticketSeeting->specTicketSetting->data)){
                                        foreach($tt->ticketSeeting->specTicketSetting->data as $res_data){
                                            //$seat_res += $res_data->ticketTotal * $schedule_sum;
                                            $seat_res += $res_data->ticketTotal ;//* $schedule_sum;
                                        }
                                    }
                                    $seat_type_count = array(
                                        'RES' => $seat_res,                        
                                        'SALE' => $seat_sale,
                                        'UNSET' => $seat_un,
                                    );

                                    $respective_data = $this->getRespectiveData($mapData, $seat_type_count);

                                    $RES = 0;
                                    $SALE = 0;
                                    $UNSET = 0;
                                    $respective_total = 0;

                                    foreach($respective_data as $respective){
                                        foreach($respective as $rule){
                                            $respective_total ++;
                                            $RES += $rule['RES'];
                                            $SALE += $rule['SALE'];
                                            $UNSET += $rule['UNSET'];
                                        }
                                    }
                                    
                                    $seat_res = $RES + $seat_res * ($schedule_sum - $respective_total);
                                    $seat_sale = $SALE + $seat_sale * ($schedule_sum - $respective_total);
                                    $seat_un = $UNSET + $seat_un * ($schedule_sum - $respective_total);

                                }
                            }else{
                                $seat_free = $tt->ticketSeeting->ticketSetting->seatQty;
                            }
                           
                            $performance_inf[] = array(
                                'performance_id'        => $item['performance_id'],
                                'performance_name'      => $item['performance_name'],
                                'disp_status'           => $disp_status,
                                'trans_flg'             => $item['trans_flg'],
                                'sale_type'             => $item['sale_type'],
                                'RES'                   => $seat_res,
                                'SALE'                  => $seat_sale,
                                'UNSET'                 => $seat_un,
                                'stock_limit'           => $seat_free,
                                's_pid'                 => '',
                                'cnt_inpay_rev'         => 0,                  
                                'cnt_inpay_free'        => 0,                  
                                'cnt_sale_rev'          => 0,              
                                'cnt_sale_free'         => 0, 
                                'cnt_rev_issue'         => 0,                    
                                'subtotal'              => 0,             
                                'sch_kbn'               => $item['sch_kbn'],
                            );
                        }
                    }
                // }
            }

           return $performance_inf;
                    
        }catch (Exception $e) {
            Log::error('getPerformationSellInf :'.$e->getMessage());
            return false;
        }
    } 
    /**
     * 更新 stock limit 資料
     * @param bool
     */
    public function updateSaleData($data){
        try {
            $this->NonreservedStockModal->update($data);

            return true;
        }catch (Exception $e) {
            Log::error('updateSaleData :'.$e->getMessage());
            return false;
        }
    }
    /**
     * 取得無席位資料
     * @param bool
     */
    public function getNonreservedStockId($stock_id){
       try {
            $this->NonreservedStockModal = NonreservedStockModal::findOrFail($stock_id);
            
            return true;
        }catch (Exception $e) {
            Log::error('getSaleReservationNo :'.$e->getMessage());
            return false;
        }

    }
    /**
     * 取指定席票資料
     * @param  $seat_class_id
     * @param  $status
     * @return SeatSaleModel
     */
    public function getSeatClassIdData($seat_class_id, $status, $schedule_id){
        return   $this->SeatSaleModel->where('seat_class_id', $seat_class_id)
                                     ->where('schedule_id', $schedule_id)
                                     ->SeatStatus($status)
                                     ->get();
    }
     /**
     * 取得場次
     * @param  $performance_id
     * @return schedule
     */
    public function getSchedule($performance_id){
        return  $this->EvenManageModel::find($performance_id)
                                                ->schedule()
                                                ->get();
    }
     /**
     * 取得票總額（票價 + 票手續費）
     * @param  $order_id
     * @return seatSale
     */
    public function getOrderPriceSum($order_id){
        return  $this->GeneralReservationModel::find($order_id)
                                                ->seatSale()
                                                ->select(  
                                                    DB::raw('sum(sale_price+
                                                                commission_sv +
                                                                commission_payment +
                                                                commission_ticket +
                                                                commission_delivery +
                                                                commission_sub +
                                                                commission_uc)
                                                                as cost_sum'))
                                                ->get();
    }
    /**
     * 取得訂單是幸福
     * @param  $order_id
     * @return GeneralReservationModel
     */
    public function getOrderCommissionSum($order_id){
        return  $this->GeneralReservationModel::where('order_id', $order_id)
                                                ->select(  
                                                    DB::raw('sum(commission_sv +
                                                                 commission_payment +
                                                                 commission_ticket +
                                                                 commission_delivery +
                                                                 commission_sub +
                                                                 commission_uc)
                                                                 as commission_sum'))
                                                ->get();
    }

    /**
     * 依狀態取得訂單
     * @param  $performance_id
     * @return Count of in-payment seat
     */
    public function getInpaymentCnt($schedule_id){
        $tt = $this->getReneralReservation($schedule_id,2);
                    //->where('reserve_expire','>=',now()); // 20201027 LST Kei.O 未使用のメソッドだが念のため修正
                    // ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', $time['now']);
        return $tt;
    }

    
    /**
     * 依狀態取得訂單
     * @param  $schedule_id
     * @param  $seat_status
     * @return generalReservation
     */
    public function getReneralReservation($schedule_id, $seat_status){
        return  $this->ScheduleModel::find($schedule_id)
                                        ->generalReservation()
                                        ->SeatStatus($seat_status)
                                        ->get();
    }
    /**
     * 取得取消訂單
     * @param  $order_id
     * @return GeneralReservationModel
     */
    public function getCancelOrder($order_id){
        return  $this->GeneralReservationModel::find($order_id)
                                                ->cancelOrder()
                                                ->get();
    }
    /**
     * 更新票卷價錢
     * @param $data
     * @return $seat_sale
     */
    public function updateSalePrice($data){
        try{
            $seat_sale = $this->SeatSaleModel::find($data['seat_sale_id']);

            $seat_sale->sale_price = $data['seat_price'];

            $seat_sale->save();

            return $seat_sale;
        }catch(Exception $e){
            Log::info('getAllSchedule ：sellManageRepositores :'.$e->getMessage());
            return false;
        }
    } 
    /**
     * 取得 Schedule idid（
     * @param $filterData
     * @return $result
     */
    public function getAllSchedule($filter_data){
        try{
            $schedule = $this->EvenManageModel::find($filter_data['performance_id'])
                                                ->schedule()
                                                ->get();

            $schedules_id = [];

            foreach($schedule as $item){
                $schedules_id[] = $item->schedule_id;
            }

            $result = array(
                'schedules_id'  => $schedules_id,
            );

            return $result;
        }catch(Exception $e){
            Log::info('getAllSchedule ：sellManageRepositores :'.$e->getMessage());
        }
    }
    /**
     * 將保留席出票資料新增 seat sale
     * @param $data
     * @return bool
     */
    public function reviseAmount($data){
       
        // try{
             $result = $this->AmountReviseModel::create( 
                 [
                     'order_id'              => $data['order_id'],
                     'amount_total'          => $data['amount_total'],
                     'revise_info'           => $data['revise_info'],
                     'amount_memo'           => $data['amount_memo'],
                     'update_account_cd'     => $data['account_cd'],
                 ]
             );
             
             if($result){
                 return true;
             }else{
                 throw new Exception('revise amount 新增失敗');
             }
            
        //  }catch(Exception $e){
        //      Log::info('reviseAmount 錯誤:'.$e->getMessage());
 
        //      return false;
        //  }
     }
     /**
      * 取得訂單金額修改資料
      * @param $filterData
      * @return $result
      */
     public function getReviseAmountInf($filter_data){
         try{
             $general_reservation = $this->GeneralReservationModel::find($filter_data['order_id']);
             
             $amount_revise = $general_reservation->amountRevise()
                                                  ->get();
             
             $result = array(
                 'status'                => false,
                 'order_id'              => '',
                 'amount_status'         => '',
                 'amount_total'          => '',
                 'revise_info'           => '',
                 'amount_memo'           => '',
                 'update_account'        => '',
                 'created_at'            => '',
             );
 
                                                 
             if(!$amount_revise->isEmpty()){
                 $account = $this->UserAccountModel::find($amount_revise[0]['update_account_cd']);
                 
                 $result['status']           = true;
                 $result['order_id']         = $amount_revise[0]['order_id'];
                 $result['amount_status']    = $amount_revise[0]['amount_status'];
                 $result['amount_total']     = intval($amount_revise[0]['amount_total']);
                 $result['revise_info']      = json_decode($amount_revise[0]['revise_info'], true);
                 $result['amount_memo']      = $amount_revise[0]['amount_memo'];
                 $result['update_account']   = $account['account_code'];
                 $result['created_at']       = $amount_revise[0]['created_at']->format('Y-m-d H:i:s');
             }
            
             return $result;
         }catch(Exception $e){
             Log::info('getReviseAmountInf ：sellManageRepositores :'.$e->getMessage());
         }
     }

    /**
     * 取得 stage_num 
     * @param $filterData
     * @return $result
     */
    public function getStagNameInfo($filter_data){
        try{
            $stage_name = $this->StagenameModel::find($filter_data['stcd']);

            $result = array(
                'stage_num' => $stage_name['stage_num'],
            );

            return $result;
        }catch(Exception $e){
            Log::info('getStagNameInfo ：sellManageRepositores :'.$e->getMessage());
        }
    }
    /**
     * 取得保留席資料
     * @param $filterData
     * @return $result
     */
    public function getSeatInfo($filterData){
        try{
            $seats = array();

            $seat = $this->SeatModel::where('alloc_seat_id', $filterData['alloc_seat_id'])
                                    ->get();

            foreach($seat as $data){ 
                $hall_seats =  $this->HallSeatModel::with(['floor', 'block'])
                                                    ->where('seat_id', $data['seat_id'])
                                                    ->get();
             
                foreach($hall_seats as $hall_seat){
                    $sinfo = (object)[
                        'gate'       =>  $hall_seat->gate, 
                        'floor'      =>  $hall_seat->floor->floor_name, 
                        'block'      =>  $hall_seat->block->block_name,  
                        'line'       =>  $hall_seat->seat_cols, 
                        'num'        =>  $hall_seat->seat_number, 
                    ];
                   
                    $seats[]   = (object)[
                        'serial'            => $hall_seat->prio_seat, 
                        'type'              => 1,
                        'sid'               => null,
                        'seq'               => $hall_seat->seat_seq,
                        'disp'              => true,
                        'tkcd'              => null,
                        'sinfo'             => $sinfo,
                    ];
                }
            }

            $result = $seats;
           
            return $result;
        }catch(Exception $e){
            Log::info('getScheduleInf ：sellManageRepositores :'.$e->getMessage());
        }
    }
    /**
     * 取得公演場次資料
     * @param $filterData
     * @return $result
     */
    public function getScheduleInf($filterData){
        try{
            $schedule    = $this->ScheduleModel::find($filterData['schedule_id']);  
            $performance = $schedule->performance()
                                    ->get();
            $user        = $schedule->user()
                                    ->get();
            
            $result = array(
                'distributor_code'   =>  $user[0]['user_code'],
                'performance_code'   =>  $performance[0]['performance_code'],
                'stage_code'         =>  $schedule['stcd'],
                'performance_date'   =>  $schedule['performance_date'],
                'start_time'         =>  $schedule['start_time'],
            );
           
            return $result;
        }catch(Exception $e){
            Log::info('getScheduleInf ：sellManageRepositores :'.$e->getMessage());
        }
    }
    /**
     * 取得票種票別名稱
     * @param $filterData
     * @return $result
     */
    public function getSeatTicketType($filterData){
        try{
            $performance_id = $filterData['performance_id'];
            $seatsTittle     = [];
            $ticketsTittle  = [];

            $seatClass =  SeatClassModel::select(
                                                    'seat_class_id',
                                                    'seat_class_name'
                                                )
                                ->with(['ticketClass' => function ($query){
                                    $query->select(['seat_class_id', 'ticket_class_id', 'ticket_class_name']);
                                }])
                                ->where('performance_id', $filterData['performance_id'])
                                ->get();
            
            foreach($seatClass as $data){
                $seatsTittle[] = $data->seat_class_name;
                foreach($data->ticketClass as $ticketData){
                    $ticketsTittle[] = $ticketData->ticket_class_name;
                }
            }

            $reserveClass = ReserveModel::select(
                                                    'reserve_name'
                                                )
                                ->where('performance_id', $filterData['performance_id'])
                                ->get();

            foreach($reserveClass as $data){
                $seatsTittle[] = $data->reserve_name;
            }      

            $result = array(
                'seatsTittle'   => array_unique($seatsTittle), 
                'ticketsTittle' => array_unique($ticketsTittle),
            );
        
            return $result;
        }catch(Exception $e){
            Log::info('getSeatTicketType ：sellManageRepositores :'.$e->getMessage());
        }
    }
    /**
     * 將保留席出票資料新增 seat sale
     * @param $data
     * @return $result
     */
    public function insertCancelOrder($data){
       
        try{
            $result = $this->CancelOrderModel::create( 
                [
                    'order_id'              => $data['order_id'],
                    'status'                => 1,
                    'refund_kbn'            => $data['refund_kbn'],
                    'refund_inf'            => $data['refund_inf'],
                    'refund_payment'        => $data['refund_payment'],
                    'update_account_cd'     => $data['update_account_cd'],
                ]
            );
            
            
            return $result;
        }catch(Exception $e){
            Log::info('insertCancelOrder :'.$e->getMessage());
        }
    }
    /**
     * 將票轉換為訂單取消狀態
     * 
     * @param $data
     * @return $result
     */
    public function seatSaleCancerStatus($data){
        try{
            $resutlt =  $this->SeatSaleModel->where('seat_sale_id', $data['seat_sale_id'])
                                            ->update(
                                                    [
                                                        'seat_status'   =>$data['seat_status']
                                                    ]
                                                );
            
            return $resutlt;
        }catch(Exception $e){
            Log::error('[seatSaleCancerStatus] error:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 取得 seat sale 資料
     * 
     * @param $data
     * @return $result
     */
    public function getOrderSeatSale($data){
        try{
            $resutlt =  $this->SeatSaleModel->select(
                                                    'seat_sale_id',
                                                    'seat_status'
                                                )
                                            ->where('order_id', $data['order_id'])
                                            ->get()
                                            ->toArray();
            
            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getSeatSaleData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 將票轉換為訂單取消狀態
     * 
     * @param $data
     * @return $result
     */
    public function generalReservationCancerStatus($data){
        try{
            $resutlt =  $this->GeneralReservationModel->where('order_id',  $data['order_id'])
                                                      ->update(
                                                                [
                                                                    'cancel_flg'    => true,    
                                                                    'mobapass_trans_flg' => false
                                                                ]
                                                            );
            
            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getSeatSaleData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 取得自由席資料
     * @param $data
     * @return $result
     */
    public function getFreeSeatData1($performance_id){
        try{
            $result = $this->SeatClassModel->select(
                                                        'GL_SEAT_CLASS.*'
                                                )
                                        ->where('performance_id', '=', $performance_id)
                                        ->where('seat_class_kbn', '=', 2)
                                        ->orderBy('disp_order')
                                        ->get();
                                        //->toArray(); //STS 2021/08/06 Task 25
            return $result;
        }catch(Exception $e){
            Log::info('insert seat sale :'.$e->getMessage());
        }
    }
    //STS 2021/08/06 Task 25 START Fix
    /**
     * 取得自由席資料
     * @param $data
     * @return $result
     */
    public function getSelectSeatData($performance_id){
        try{
            $result = $this->SeatClassModel->select(
                                                        'GL_SEAT_CLASS.*'
                                                )
                                        ->where('performance_id', $performance_id)
                                        ->where('seat_class_kbn', '=', 1)
                                        ->orderBy('disp_order')
                                        ->get();
            return $result;
        }catch(Exception $e){
            Log::info('get select seat data error [getSelectSeatData] :'.$e->getMessage());
        }
    }
    //STS 2021/08/06 Task 25 END
    /**
     * 將保留席出票資料新增 seat sale
     * @param $data
     * @return $result
     */
    public function insertSeatSale($data){
       
        try{
            $this->SeatSaleModel = SeatSaleModel::create($data);
           
            return $this->SeatSaleModel;
        }catch(Exception $e){
            Log::error('insert seat sale :'.$e->getMessage());
        }
    }
    /**
     * 取得席位資料
     * @param $data
     * @return $result
     */
    public function getSeatAllocReserveID(int $allocID, int $scheduleID){
        try{
            $this->SeatModel = SeatModel::findOrFail($allocID);
            $stageSeat = $this->SeatModel->stageSeats->where('schedule_id', $scheduleID)->first();
            if($stageSeat) 
                return $stageSeat->reserve_code;
            else 
                return $this->SeatModel->reserve_code;
        }catch(Exception $e){
            Log::error('getSeatAllocClassID :'.$e->getMessage());
        }
    }

    /**
     * get Free Seat Data
     * 
     * @param $seat_class_id, $scheduleId
     * @return $result
     */
    public function getFreeSeatNum($seat_class_id, $scheduleId){
        try{
            $result = 0;
            $currentNum = SeatSaleModel::where('schedule_id',$scheduleId)
                            ->where('seat_class_id',$seat_class_id)
                            ->max('seat_seq');

            $result = $currentNum+1;
            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 |  get Free Seat Data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }

    /**
     * 增加保留席出票資料
     * @param $data
     * @return $result
     */
    public function insertReserve($data){
        try{
            $this->GeneralReservationModel = GeneralReservationModel::create($data);
            // $this->GeneralReservationModel = new GeneralReservationModel;
            // $this->GeneralReservationModel->GLID =  $data['GLID'];
            // $this->GeneralReservationModel->reserve_no = $data['reserve_no'];
            // $this->GeneralReservationModel->receipt_kbn = $data['receipt_kbn'];
            // $this->GeneralReservationModel->member_id = $data['member_id'];
            // $this->GeneralReservationModel->consumer_name =  $data['consumer_name'];
            // $this->GeneralReservationModel->reserve_date = $data['reserve_date'];
            // $this->GeneralReservationModel->pay_method=$data['pay_method'];
            // $this->GeneralReservationModel->tel_num = $data['tel_num'];
            // $this->GeneralReservationModel->pickup_method = $data['pickup_method'];
            // $this->GeneralReservationModel->mail_address = $data['mail_address'];
            // $this->GeneralReservationModel->pickup_st_date = $data['pickup_st_date'];
            // $this->GeneralReservationModel->pickup_due_date = $data['pickup_due_date'];
            // $this->GeneralReservationModel->receive_account_cd = $data['receive_account_cd'];
            // $this->GeneralReservationModel->commission_sv = $data['commission_sv'];
            // $this->GeneralReservationModel->commission_payment = $data['commission_payment'];
            // $this->GeneralReservationModel->commission_ticket = $data['commission_ticket'];
            // $this->GeneralReservationModel->commission_delivery = $data['commission_delivery'];
            // $this->GeneralReservationModel->commission_sub = $data['commission_sub'];
            // $this->GeneralReservationModel->commission_uc = $data['commission_uc'];
            // $this->GeneralReservationModel->update_account_cd = $data['update_account_cd'];
            // $this->GeneralReservationModel->SID = $data['SID'];
            // $this->GeneralReservationModel->save();
            return $this->GeneralReservationModel->order_id;
        }catch(Exception $e){
            Log::info('cheack seat is order :'.$e->getMessage());
        }
    }

    /**
     * 增加保留席出票資料
     * @param $data
     * @return $result
     */
    public function setSeatAllocFail(){
        try{
            $this->GeneralReservationModel->cancel_flg = 1;
            $this->GeneralReservationModel->save();
            $this->SeatSaleModel->seat_status = -99;
            $this->SeatSaleModel->save();
        }catch(Exception $e){
            Log::info('setSeatAllocFail :'.$e->getMessage());
        }
    }

    /**
     * 檢查坐席是否被預訂
     * @param $data
     * @return bool
     */
    public function cheackSeatIsOrder($data)
    {
        try{
            $result = $this->SeatSaleModel->where('alloc_seat_id', $data['alloc_seat_id'])
                                          ->where('schedule_id', $data['schedule_id'])
                                          ->seatStatusNotCancel()
                                          ->exists();

            return $result;
        }catch(Exception $e){
            Log::info('cheack seat is order :'.$e->getMessage());
        }
    }
    /**
     * update performance data
     * @param array $basisInf
     * @return result 
     */ 
    public function performanceJsonUpdate($tempinfo)
    { 
        try{

            $result = $this->EvenManageModel->where('performance_id', $tempinfo['performance_id'])
                                            ->update(
                                                    [
                                                        'temporary_info'=>$tempinfo['temporary_info'],
                                                        'update_account_cd'=>$tempinfo['account_cd'],
                                                    ]
                                                );
           if(!$result){
               throw new Exception ('update error');
           }           
                                                         
       }catch(Exception $e){
           Log::info('table performance insert data :'.$e->getMessage());
       }
    }  
    /**
     * get performance json data
     * 
     * @param $performance_id 
     * @return array
     */
    public function getPerformanceJsonData($performance_id){
       try{
            $result = $this->EvenManageModel->select(
                                                'temporary_info'
                                            )
                                            ->where('performance_id', $performance_id)
                                            ->get();
            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 | get performance json data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get Free Seat Total
     * 
     * @param $scheduleId
     * @return $result
     */
    public function getFreeSeatTotal($scheduleId){
        try{
            $result = $this->NonreservedStockModal->select(DB::raw('SUM(stock_limit) as total'))
                                                ->where('schedule_id', $scheduleId)
                                                ->groupBy('schedule_id')
                                                ->get();
        
            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 |  get Free Seat Total | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get Free Seat Data
     * 
     * @param $seat_class_id, $scheduleId
     * @return $result
     */
    public function getFreeSeatData($seat_class_id, $scheduleId){
        try{
            $result = $this->NonreservedStockModal->where('seat_class_id', $seat_class_id)
                                                ->where('schedule_id', $scheduleId)
                                                ->get();

            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 |  get Free Seat Data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }

    /**
     * insert Null Stage Seat Data
     * 
     * @param  $scheduleId, $account_cd, $data
     * @return $result
     */
    public function insertNullStageSeatData($scheduleId, $account_cd, $data){
        try{
            $result = $this->StageSeatModal->insert([
                                                    'alloc_seat_id'=>$data['alloc_seat_id'],
                                                    'schedule_id'=>$scheduleId,
                                                    'update_account_cd'=>$account_cd,
                                                ]);
            return  $result;
        }catch(Exception $e){
            Log::info('error code : 1 |  insert Null Stage Seat Data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * update Null Stage Seat Data
     * 
     * @param  $scheduleId, $account_cd, $data
     * @return $result
     */
    public function updateNullStageSeatData($scheduleId, $account_cd, $data){
       try{
            $result = $this->StageSeatModal->where('schedule_id', $scheduleId)
                                            ->where('alloc_seat_id', $data['alloc_seat_id'])
                                            ->update([
                                                        'seat_class_id'=> null,
                                                        'reserve_code'=> null,
                                                        'update_account_cd'=> $account_cd
                                                    ]);
                                                   
            return $result;                              
        }catch(Exception $e){
            Log::info('error code : 1 |update Null Stage Seat Data| data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * insert Class Stage Seat Data
     * 
     * @param  $scheduleId, $account_cd, $data
     * @return $result
     */
    public function insertClassStageSeatData($data){
       try{

            $result = $this->StageSeatModal->insert([
                                                    'alloc_seat_id' => $data['alloc_seat_id'],
                                                    'schedule_id' => $data['schedule_id'],
                                                    'seat_class_id' => $data['seat_class_id'],
                                                    'reserve_code' => $data['reserve_code'],
                                                    'update_account_cd' => $data['update_account_cd'],
                                                ]);
                                                
            return  $result;
        }catch(Exception $e){
            Log::info('error code : 1 | insert Stage Seat Data| data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * update Class Stage Seat Data
     * 
     * @param  $scheduleId, $account_cd, $data
     * @return $result
     */
    public function updateClassStageSeatData($data){
      // try{
            $result = $this->StageSeatModal->where('schedule_id', $data['schedule_id'])
                                            ->where('alloc_seat_id', $data['alloc_seat_id'])
                                            ->update([
                                                        'seat_class_id' => $data['seat_class_id'],
                                                        'reserve_code' => $data['reserve_code'],
                                                        'update_account_cd' => $data['update_account_cd'],
                                                    ]);
                                                   
            return $result;                              
        // }catch(Exception $e){
        //     Log::info('error code : 1 | get Seat Data | data error :'.$e->getMessage());
        //     App::abort(500);
        // }
    }
    /**
     * insert Stage Seat Data
     * 
     * @param  $scheduleId, $account_cd, $data
     * @return $result
     */
    public function insertStageSeatData($scheduleId, $account_cd, $reserve_code, $data){
        try{
            $result = $this->StageSeatModal->insert([
                                                    'alloc_seat_id'=>$data['alloc_seat_id'],
                                                    'schedule_id'=>$scheduleId,
                                                    'reserve_code'=>$reserve_code,
                                                    'update_account_cd'=>$account_cd,
                                                ]);
            return  $result;
        }catch(Exception $e){
            Log::info('error code : 1 | insert Stage Seat Data| data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * update Stage Seat Data
     * 
     * @param  $scheduleId, $account_cd, $data
     * @return $result
     */
    public function updateStageSeatData($scheduleId, $account_cd, $reserve_code, $data){
        try{
            $result = $this->StageSeatModal->where('schedule_id', $scheduleId)
                                            ->where('alloc_seat_id', $data['alloc_seat_id'])
                                            ->update([
                                                        'seat_class_id'=> null,
                                                        'reserve_code'=> $reserve_code,
                                                        'update_account_cd'=> $account_cd
                                                    ]);
            return $result;                              
        }catch(Exception $e){
            Log::info('error code : 1 | get Seat Data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * delete Stage Seat Data
     * 
     * @param alloc_seat_id
     * @return $result
     */
    public function deleteStageSeatData($stage_seat_id){
        //try{
            $result = $this->StageSeatModal->where('stage_seat_id', $stage_seat_id)
                                        ->delete();
            
            return $result;
        // }catch(Exception $e){
        //     Log::info('error code : 1 | get Seat Data | data error :'.$e->getMessage());
        //     App::abort(500);
        // }
    }
    /**
     * get Seat Data
     * 
     * @param $alloc_seat_id, $scheduleId
     * @return $result
     */
    public function getSeatData($alloc_seat_id, $scheduleId){
        try{
            $result = $this->SeatModel->select(
                                            'GL_SEAT.seat_class_id AS seat_class_id',
                                            'GL_SEAT.reserve_code AS reserve_code',
                                            'GL_STAGE_SEAT.stage_seat_id AS stage_seat_id',   
                                            'GL_STAGE_SEAT.seat_class_id AS stage_seat_class_id',   
                                            'GL_STAGE_SEAT.reserve_code AS stage_reserve_code'
                                        )
                                        ->leftJoin('GL_STAGE_SEAT', function($join) use ($scheduleId)
                                        {
                                            $join->on('GL_STAGE_SEAT.alloc_seat_id', '=', 'GL_SEAT.alloc_seat_id')
                                                ->where('GL_STAGE_SEAT.schedule_id', '=',$scheduleId);
                                        })
                                        ->where('GL_SEAT.alloc_seat_id', $alloc_seat_id)
                                        ->get();
            
            return $result[0];
        }catch(Exception $e){
            Log::info('error code : 1 | get Seat Data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * insert reserve seat data
     * 
     * @param $reserveData, $performanceId, $account_cd
     * 
     */
    public function insertReserveSeat($account_cd, $performanceId, $reserveData){
        try{
            $result = $this->ReserveModel->insertGetId(
                                                    [
                                                        'performance_id'=>$performanceId,
                                                        'reserve_name'=>$reserveData['reserve_name'],
                                                        'reserve_symbol'=>$reserveData['text'],
                                                        'reserve_color'=>$reserveData['color'],
                                                        'reserve_word_color'=>$reserveData['text_color'],
                                                        'update_account_cd'=>$account_cd,
                                                    ]
                                                );
            return  $result;
        }catch(Exception $e){
            Log::info('error code : 1 | insert reserve seat data | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * fet seat sell status
     * 
     * @param $performance_id, $schedule_id
     * @return array $result
     */
    public function getSeatSellStatuc($performance_id, $schedule_id){
        // $now = date("Y-m-d H:i:s");
        // $add_time = strtotime("-15 minutes", strtotime($now)); 
        // $expire = date('Y-m-d H:i:s', $add_time); 

        // $time = array(
        //     'now' => $now,
        //     'expire' => $expire,
        // );
                                           
        $result =  SeatSaleModel::select(
                                            'GL_SEAT_SALE.alloc_seat_id',
                                            'GL_SEAT_SALE.order_id',         
                                            'GL_SEAT_SALE.seat_status',
                                            'GL_SEAT_SALE.payment_flg',
                                            'GL_SEAT_SALE.temp_reserve_date',
                                            'GL_GENERAL_RESERVATION.cancel_flg',
                                            'GL_GENERAL_RESERVATION.pay_method',
                                            // DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour as reserve_expire')
                                            'GL_GENERAL_RESERVATION.reserve_expire'
                                        )
                                ->leftJoin('GL_GENERAL_RESERVATION', function($join)
                                        {
                                            $join->on('GL_SEAT_SALE.order_id', '=', 'GL_GENERAL_RESERVATION.order_id')
                                                ->where('GL_GENERAL_RESERVATION.cancel_flg', '=', 0);
                                        }
                                    )
                                ->where('schedule_id', $schedule_id)
                                ->where('seat_status', '>', 0)
                                // ->where(function ($query) use ($time){
                                //     $query->whereIn('GL_SEAT_SALE.seat_status', [3])
                                //             ->orWhere(function ($query) {
                                //                 $query->where('GL_SEAT_SALE.payment_flg', '<>', 0)
                                //                         ->where('GL_SEAT_SALE.seat_status', '>', 1);
                                //             })
                                //             ->orWhere(function ($query) use ($time) {
                                //                 $query->where(function ($query) use ($time){
                                //                         $query->where('GL_SEAT_SALE.seat_status', '=', 1)
                                //                             ->where(function ($query) use ($time){
                                //                                 $query->where(function ($query) use ($time) {
                                //                                             $query->whereNotNull('GL_SEAT_SALE.order_id')
                                //                                                   ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', $time['now']);
                                //                                         })
                                //                                         ->orWhere(function ($query) use ($time) {
                                //                                             $query->whereNull('GL_SEAT_SALE.order_id')
                                //                                                     ->where('GL_SEAT_SALE.temp_reserve_date', '>', $time['expire']);
                                //                                         });
                                //                             });      
                                //                 });
                                //             })
                                //             ->orWhere(function ($query) use ($time) {
                                //                 $query->where('GL_SEAT_SALE.seat_status', '=', 2)
                                //                         ->whereNotNull('GL_SEAT_SALE.order_id')
                                //                         ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', $time['now']);
                                //             });
                                //     })
                                    ->get(); 
        return $result;
    }
    /**
     * get SeatViews Data
     * @param $performance_id, $schedule_id
     * @return array $result
     */
    public function getSeatViewsData($performance_id, $schedule_id){
        try{
            $result =  $this->VSeatOfStageModal->select(
                                                    'GL_V_Seat_of_Stage.*',
                                                    'GL_HALL_SEAT.*',
                                                    'GL_FLOOR.*',
                                                    'GL_BLOCK.*',
                                                    'GL_FLOOR.image_file_name AS floor_image_file_name'
                                                )
                                                ->where('GL_V_Seat_of_Stage.performance_id', $performance_id)
                                                ->where('GL_V_Seat_of_Stage.schedule_id', $schedule_id)
                                                ->leftJoin('GL_HALL_SEAT', 'GL_V_Seat_of_Stage.seat_id', '=', 'GL_HALL_SEAT.seat_id')
                                                ->leftJoin('GL_FLOOR', 'GL_HALL_SEAT.floor_id', '=', 'GL_FLOOR.floor_id')
                                                ->leftJoin('GL_BLOCK', 'GL_HALL_SEAT.block_id', '=', 'GL_BLOCK.block_id')
                                                ->get();
            
            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 | getSeatViewsData function error | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /***
     * get seat sell data
     * 
     * @param $alloc_seat_id $schedule_id $seat_class_id
     * 
     */
    public function getSellData($alloc_seat_id, $schedule_id, $seat_class_id){
       
        $result = $this->SeatSaleModel->select(
                                            'GL_SEAT_SALE.seat_sale_id'
                                        )
                                        ->where('alloc_seat_id', $alloc_seat_id)
                                        ->where('schedule_id', $schedule_id)
                                        ->where('seat_class_id', $seat_class_id)
                                        ->count();
        
        return $result;
    }
    /**
     * gtet performance reservation data
     * 
     * @param $performance_id 
     * @return array
     */
    public function getSeatTypeData($performance_id){
        try{
            $result = $this->SeatClassModel->select(
                                            'GL_SEAT_CLASS.*'
                                        )
                                        ->where('performance_id', $performance_id)
                                        ->orderBy('disp_order') //STS Task 25 2021/09/07
                                        ->get();
            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 | getSeatTypeData function error | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * gtet performance reservation data
     * 
     * @param $performance_id 
     * @return array
     */
    public function getReservationData($performance_id){
        try{
            $result = $this->ReserveModel->select(
                                            'GL_RESERVE.*'
                                        )
                                        ->where('performance_id', $performance_id)
                                        ->orderBy('reserve_code', 'asc')
                                        ->get();
            return $result;
        }catch(Exception $e){
            Log::info('error code : 1 | get Reservation Data function error | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get schedule data
     * @parm string schedule id
     * @return array
     */
    public function getScheduleDataMapPage($scheduleId){
        try{
            $result = $this->ScheduleModel->select(
                                                'GL_SCHEDULE.performance_id',
                                                'GL_SCHEDULE.performance_date',
                                                'GL_SCHEDULE.start_time'
                                            )
                                            ->where('schedule_id', $scheduleId)
                                            ->get();

            return $result[0];
        }catch(Exception $e){
            Log::info('error code : 1 | getScheduleDataMapPage function error | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get general reservation id
     * @return general reservation id
     */
    public function getdetailData($scheduleId){
       
        try{
            $result = $this->ScheduleModel->select(
                                                    'GL_PERFORMANCE.performance_id',
                                                    'GL_PERFORMANCE.performance_name',
                                                    'GL_PERFORMANCE.GLID',
                                                    'GL_PERFORMANCE.status',
                                                    'GL_PERFORMANCE.trans_flg',
                                                    'GL_PERFORMANCE.seatmap_profile_cd',
                                                    'GL_PERFORMANCE.sch_kbn',
                                                    'GL_SCHEDULE.performance_date',
                                                    'GL_SCHEDULE.start_time'
                                                )
                                            ->where('schedule_id', $scheduleId)
                                            ->leftJoin('GL_PERFORMANCE', 'GL_SCHEDULE.performance_id', '=', 'GL_PERFORMANCE.performance_id')
                                            ->get();
                
            return $result[0];

        }catch(Exception $e){
            Log::info('get schedule data error [getdetailData] :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get general reservation id
     * @return general reservation id
     */
    public function getGeneralReservationId($data){
        try{
            $result =  $this->SeatSaleModel->select('GL_SEAT_SALE.order_id')
                                            ->leftJoin('GL_GENERAL_RESERVATION', 'GL_SEAT_SALE.order_id', '=', 'GL_GENERAL_RESERVATION.order_id')
                                            ->where('GL_SEAT_SALE.schedule_id', $data['scheduleId'])
                                            ->where('GL_SEAT_SALE.order_id','<>',null);
                    
            //過濾資料
            if($data['filter']){
                //關鍵字
                if($data['filterData']['keyword']){
                    $keyword = $data['filterData']['keyword'];
                    $result = $result->Where(function($q) use ($keyword){
                                    $q->where('GL_GENERAL_RESERVATION.reserve_no', 'like', '%'.$keyword.'%')
                                    //STS - 2021/6/18 Task 21 - #fix search with 非会員 - START
                                        ->orWhere(function($q2) use ($keyword){
                                            if(!str_contains(trans('sellManage.S_EventDetailNoneMember'), $keyword)){

                                        $q2->where('GL_GENERAL_RESERVATION.member_id', 'like', '%'.$keyword.'%')
                                        ->where('GL_GENERAL_RESERVATION.member_id', '!=', 'gettiis$[N_M]');
                                    }
                                        else $q2->where('GL_GENERAL_RESERVATION.member_id', '=', 'gettiis$[N_M]');

                                      })
                                      //->orWhere('GL_GENERAL_RESERVATION.member_id', 'like', '%'.$keyword.'%')
                                        //STS - 2021/6/18 Task 21 - #fix search with 非会員 - END
                                      ->orWhere('GL_GENERAL_RESERVATION.consumer_name', 'like', '%'.$keyword.'%')
                                      ->orWhere('GL_GENERAL_RESERVATION.mail_address', 'like', '%'.$keyword.'%')
                                      ->orWhere('GL_GENERAL_RESERVATION.tel_num', 'like', '%'.$keyword.'%');
                                });
                }
                //付款方式
                $result = $result->whereIn('GL_GENERAL_RESERVATION.pay_method', $data['filterData']['pay_method']);
                //取票方式
                if(!empty($data['filterData']['pickup_method'])){
                    $result = $result->whereIn('GL_GENERAL_RESERVATION.pickup_method', $data['filterData']['pickup_method']);
                }
                //訂單時間
                if($data['filterData']['dateRangeStar'] && $data['filterData']['dateRangeEnd']){
                    $result = $result->whereBetween('GL_GENERAL_RESERVATION.reserve_date', [$data['filterData']['dateRangeStar'], $data['filterData']['dateRangeEnd']]);
                }
                //是否付款
                $result = $result->whereIn('GL_SEAT_SALE.payment_flg', $data['filterData']['receipt']);
                //是否取票
                if($data['filterData']['issue'] != 2){
                    $result = $result->whereIn('GL_SEAT_SALE.issue_flg', $data['filterData']['issue']);
                }
                //票種
                if($data['filterData']['seatType'] !== "all"){
                    $result = $result->where('GL_SEAT_SALE.seat_class_name', '=', $data['filterData']['seatType']);
                }
                //票別
                if($data['filterData']['ticketType'] !== "all"){
                    $result = $result->where('GL_SEAT_SALE.ticket_class_name', '=', $data['filterData']['ticketType']);
                }
                //自由座
                if($data['filterData']['seatFree'] && !$data['filterData']['seatOrder']){
                    $result = $result->whereNull('GL_SEAT_SALE.alloc_seat_id');
                }
                //對號座
                if(!$data['filterData']['seatFree'] && $data['filterData']['seatOrder']){
                    $result = $result->whereNotNull('GL_SEAT_SALE.alloc_seat_id');
                }
            }
            $result = $result->where('GL_GENERAL_RESERVATION.pay_method', '!=', 0);
            $result = $result->groupBy('GL_SEAT_SALE.order_id')
                               ->get()
                               ->toArray();
            
            return $result;

        }catch(Exception $e){
            Log::info('get schedule data error [getGeneralReservationId] :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
    *將字串部分內容替換成星號或其他符號
    * @param string $string 原始字串
    * @param string $symbol 替換的符號
    * @param int $begin_num 顯示開頭幾個字元
    * @param int $end_num 顯示結尾幾個字元
    * return string
    */
    private function replace_symbol_text($string,$symbol,$begin_num = 0,$end_num = 0)
    {
        $string_length = mb_strlen($string);
        $begin_num = (int)$begin_num;
        $end_num = (int)$end_num;
        $string_middle = '';
        
        $check_reduce_num = $begin_num + $end_num;
        
        if($check_reduce_num >= $string_length) {
            if($begin_num >= $string_length) 
            {
                $begin_num--;
                $end_num = 0;
            }
            else {
                $end_num = $string_length - $begin_num - 1;
            }
        }
        $symbol_num = $string_length - ($begin_num + $end_num);
        $string_begin = mb_substr($string, 0,$begin_num);
        $string_end = mb_substr($string, $string_length-$end_num);
        
        for ($i=0; $i < $symbol_num; $i++) {
            $string_middle .= $symbol;
        }
        return $string_begin.$string_middle.$string_end;
    }

    private function hide_mail_account($email,$symbol,$begin_num = 0,$end_num = 0)
    {
        $arr_mail = explode('@',$email,2);
        $hided_prefix = $this->replace_symbol_text($arr_mail[0],$symbol,$begin_num,$end_num);
        return $hided_prefix.'@'.$arr_mail[1];
    }
    /**
     * 
     * get general reservation data
     * @return general reservation data
     */
    public function getGeneralReservationData($orderId,$disp_privacy=true){
       
       try{
            $result =  $this->GeneralReservationModel->select(
                                                            "GL_GENERAL_RESERVATION.order_id",
                                                            "GL_GENERAL_RESERVATION.GLID",
                                                            "GL_GENERAL_RESERVATION.reserve_no",
                                                            "GL_GENERAL_RESERVATION.receipt_kbn",
                                                            "GL_GENERAL_RESERVATION.reserve_date",
                                                            "GL_GENERAL_RESERVATION.reserve_expire",
                                                            "GL_GENERAL_RESERVATION.member_kbn",
                                                            "GL_GENERAL_RESERVATION.member_id",
                                                            "GL_GENERAL_RESERVATION.consumer_name",
                                                            "GL_GENERAL_RESERVATION.consumer_kana",
                                                            "GL_GENERAL_RESERVATION.consumer_kana2",
                                                            "GL_GENERAL_RESERVATION.tel_num",
                                                            "GL_GENERAL_RESERVATION.pay_method",
                                                            "GL_GENERAL_RESERVATION.pickup_method",
                                                            "GL_GENERAL_RESERVATION.cs_payment_no",
                                                            "GL_GENERAL_RESERVATION.cs_pickup_no",
                                                            "GL_GENERAL_RESERVATION.mail_address",
                                                            "GL_GENERAL_RESERVATION.pickup_st_date",
                                                            "GL_GENERAL_RESERVATION.pickup_due_date",
                                                            "GL_GENERAL_RESERVATION.cancel_flg",
                                                            "GL_GENERAL_RESERVATION.comment",
                                                            "GL_GENERAL_RESERVATION.receive_account_cd",
                                                            "GL_GENERAL_RESERVATION.use_point",
                                                            "GL_GENERAL_RESERVATION.receive_lang",
                                                            "GL_GENERAL_RESERVATION.receipt_no",
                                                            "GL_GENERAL_RESERVATION.commission_sv",
                                                            "GL_GENERAL_RESERVATION.commission_payment",
                                                            "GL_GENERAL_RESERVATION.commission_ticket",
                                                            "GL_GENERAL_RESERVATION.commission_delivery",
                                                            "GL_GENERAL_RESERVATION.commission_sub",
                                                            "GL_GENERAL_RESERVATION.commission_uc",
                                                            "GL_GENERAL_RESERVATION.mobapass_trans_flg",
                                                            "GL_GENERAL_RESERVATION.mobapass_cancel_flg",
                                                            "GL_GENERAL_RESERVATION.update_account_cd",
                                                            "GL_GENERAL_RESERVATION.created_at",
                                                            "GL_GENERAL_RESERVATION.updated_at",
                                                            "GL_GENERAL_RESERVATION.SID",
                                                            "GL_CANCEL_ORDER.cancel_order_id",
                                                            "GL_CANCEL_ORDER.status",
                                                            "GL_CANCEL_ORDER.status_message",
                                                            "GL_CANCEL_ORDER.refund_kbn",
                                                            "GL_CANCEL_ORDER.refund_inf",
                                                            "GL_CANCEL_ORDER.refund_payment",
                                                            "GL_CANCEL_ORDER.refund_due_date",
                                                            DB::raw('GL_GENERAL_RESERVATION.commission_sv +
                                                                     GL_GENERAL_RESERVATION.commission_payment +
                                                                     GL_GENERAL_RESERVATION.commission_ticket +
                                                                     GL_GENERAL_RESERVATION.commission_delivery +
                                                                     GL_GENERAL_RESERVATION.commission_sub +
                                                                     GL_GENERAL_RESERVATION.commission_uc
                                                            as commission_sum')
                                                      )
                                                     ->where('GL_GENERAL_RESERVATION.order_id', $orderId)
                                                     ->leftJoin('GL_CANCEL_ORDER', 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_CANCEL_ORDER.order_id')
                                                     ->get();
           
            $result[0]['member_id'] = str_replace( 'gettiis$','', $result[0]['member_id'] );
            if(!$disp_privacy) {
                $result[0]['member_id'] = $this->replace_symbol_text($result[0]['member_id'],'*',1,1);
                $result[0]['consumer_name'] = $this->replace_symbol_text($result[0]['consumer_name'],'〇',1,1);
                $result[0]['consumer_kana'] = $this->replace_symbol_text($result[0]['consumer_kana'],'〇',1,1);
                $result[0]['consumer_kana2'] = $this->replace_symbol_text($result[0]['consumer_kana2'],'〇',1,1);
                $result[0]['tel_num'] = $this->replace_symbol_text($result[0]['tel_num'],'*',0,5);
                $result[0]['mail_address'] = $this->hide_mail_account($result[0]['mail_address'],'*',1,1);
            }
            return $result;

        }catch(Exception $e){
            Log::info('get schedule data error [getGeneralReservationData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get reserve seat name
     * @return general reserve name
     */
    public function getReserveSeatName($reserve_code){
        try{
            $result =  $this->ReserveModel->select(
                                                'reserve_name'
                                            )
                                           ->where('reserve_code', $reserve_code)
                                           ->get();

            return $result[0]['reserve_name'];
        }catch(Exception $e){
            Log::info('get schedule data error [getReserveSeatName]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get seat clasee name
     * @return general reserve name
     */
    public function getSeatClassKbn($seat_class_id){
        try{
            $result =  $this->SeatClassModel->select(
                                                'seat_class_kbn'
                                            )
                                            ->where('seat_class_id', $seat_class_id)
                                            ->get();
            return $result[0]['seat_class_kbn'];
        }catch(Exception $e){
            Log::info('getSeatClassKbn error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get seat type
     * @return general seat sale data
     */
    public function getSeatType($alloc_seat_id){
       
        try{
            $result =  $this->SeatModel->select(
                                                'GL_SEAT.seat_id',
                                                'GL_SEAT.reserve_code',
                                                'GL_SEAT.seat_class_id',
                                                'GL_STAGE_SEAT.stage_seat_id',
                                                'GL_STAGE_SEAT.reserve_code AS stage_seat_reserve_code',
                                                'GL_STAGE_SEAT.seat_class_id AS stage_seat_seat_class_id'
                                            )
                                        ->where('GL_SEAT.alloc_seat_id', $alloc_seat_id)
                                        // ->whereNull('GL_STAGE_SEAT.reserve_code')
                                        ->leftJoin('GL_STAGE_SEAT', 'GL_SEAT.alloc_seat_id', '=', 'GL_STAGE_SEAT.alloc_seat_id')
                                        ->get();
           
            return $result[0];

        }catch(Exception $e){
            Log::info('get schedule data error [getSeatType]:'.$e->getMessage());
        }
    }
    /**
     * 
     * 
     */
    public function getTicketData($seat_class_id){

        $result = $this->TicketClassModal->select(
                                                'ticket_class_name'
                                                )
                                         ->where('seat_class_id', $seat_class_id)
                                         ->get();

        return $result;
    }
    /**
     * 
     * 
     */
    public function getSeatPosition($seat_id){
        try{
            $result =  $this->HallSeatModel->select(
                                                'GL_FLOOR.floor_name',
                                                'GL_BLOCK.block_name',
                                                'GL_HALL_SEAT.seat_cols',
                                                'GL_HALL_SEAT.seat_number'
                                                )
                                           ->where('GL_HALL_SEAT.seat_id', $seat_id)
                                           ->leftJoin('GL_FLOOR', 'GL_HALL_SEAT.floor_id', '=', 'GL_FLOOR.floor_id')
                                           ->leftJoin('GL_BLOCK', 'GL_HALL_SEAT.block_id', '=', 'GL_BLOCK.block_id')
                                           ->get();

            return $result[0];
        }catch(Exception $e){
            Log::info('get schedule data error [getSeatPosition]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get Seat Sale Data
     * @return general seat sale data
     */
    public function getPerformanceSeatTypeData($performance_id, $scheduleId){
       
        try{
            $resutlt =  $this->SeatModel->select(
                                                    'GL_SEAT.alloc_seat_id',
                                                    'GL_SEAT.reserve_code',
                                                    'GL_SEAT.seat_id',
                                                    'GL_SEAT.seat_class_id',
                                                    'GL_STAGE_SEAT.stage_seat_id',
                                                    'GL_STAGE_SEAT.schedule_id',
                                                    'GL_STAGE_SEAT.seat_class_id As stage_seat_class_id',
                                                    'GL_STAGE_SEAT.reserve_code AS stage_reserve_code'
                                                )
                                            ->where('GL_SEAT.performance_id', $performance_id)
                                            ->leftJoin('GL_STAGE_SEAT', 'GL_SEAT.alloc_seat_id', '=', 'GL_STAGE_SEAT.alloc_seat_id')
                                            ->get();
           
            return $resutlt;

        }catch(Exception $e){
            Log::info('get schedule data error [getPerformanceSeatTypeData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get Seat Sale Data
     * @return general seat sale data
     */
    public function getScheduleSelect_RESSeatData($performance_id, $scheduleId){ //STS 2021/08/09 Task 25 
       
        try{
            $resutlt =  $this->SeatModel->select(
                                                    'GL_SEAT.alloc_seat_id',
                                                    'GL_SEAT.seat_class_id',
                                                    'GL_SEAT.reserve_code',
                                                    'GL_SEAT.seat_id',
                                                    'GL_STAGE_SEAT.stage_seat_id',
                                                    'GL_STAGE_SEAT.schedule_id',
                                                    'GL_STAGE_SEAT.seat_class_id As stage_seat_class_id',
                                                    'GL_STAGE_SEAT.reserve_code AS stage_reserve_code',
                                                    'GL_GENERAL_RESERVATION.order_id',
                                                    'GL_SEAT_SALE.sale_price',
                                                    'GL_GENERAL_RESERVATION.cancel_flg'
                                                )
                                            ->leftJoin('GL_STAGE_SEAT', function($join) use ($scheduleId)
                                            {
                                                $join->on('GL_STAGE_SEAT.alloc_seat_id', '=', 'GL_SEAT.alloc_seat_id')
                                                    ->where('GL_STAGE_SEAT.schedule_id', '=',$scheduleId);
                                            })
                                            ->leftJoin('GL_SEAT_SALE', function($join) use ($scheduleId)
                                            {
                                                $join->on('GL_SEAT.alloc_seat_id', '=', 'GL_SEAT_SALE.alloc_seat_id')
                                                    ->where('GL_SEAT_SALE.schedule_id', '=',$scheduleId)
                                                    ->where('GL_SEAT_SALE.seat_status', '=',3) //STS 2021/08/09 Task 25
                                                    ->where('GL_SEAT_SALE.sale_type', '=',0) //STS 2021/08/09 Task 25
                                                    ->where('GL_SEAT_SALE.payment_flg','=',1);
                                                    //  ->where(function($query) {
                                                    //      $query->whereNotNull('order_id')
                                                    //             ->orwhere(function($q2) {
                                                    //                 $q2->whereNull('order_id')
                                                    //                     ->where('temp_reserve_date','>',date('Y-m-d H:i:s', strtotime('+10 min')));
                                                    //             });
                                                    //  });
                                            })
                                            ->leftJoin('GL_GENERAL_RESERVATION', function($join) use ($scheduleId)
                                            {
                                                $join->on('GL_SEAT_SALE.order_id', '=', 'GL_GENERAL_RESERVATION.order_id')
                                                     ->where( function($q1) {
                                                         $q1->where('cancel_flg',0);
                                                            // ->where( function($q2){
                                                            //     $q2->where('seat_status',3)
                                                            //         ->orwhere(function($q3){
                                                            //             $q3->where('seat_status',2)
                                                            //                ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', now());
                                                            //         });
                                                            // });
                                                     });
                                            })
                                            ->where('GL_SEAT.performance_id', $performance_id)
                                            ->where(function($e) {
                                                $e->whereNotNull('GL_SEAT.reserve_code')
                                                  ->orWhereNotNull('GL_STAGE_SEAT.reserve_code')
                                                  //STS 2021/8/06 Task 25
                                                  ->orWhereNotNull('GL_SEAT.seat_class_id')
                                                  ->orWhereNotNull('GL_STAGE_SEAT.seat_class_id');
                                            })
                                            ->get();
            return $resutlt;

        }catch(Exception $e){
            Log::info('get schedule data error [getScheduleSelect_RESSeatData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get Seat Sale Data
     * @return general seat sale data
     */
    public function getReservationSeatData($filterData){
        $performanceId  = $filterData['performance_id'];
        $scheduleId     = $filterData['scheduleId'];
        try{
            $result =  $this->SeatModel->select(
                                                    'GL_SEAT.alloc_seat_id',
                                                    'GL_SEAT.reserve_code',
                                                    'GL_SEAT.seat_id',
                                                    'GL_SEAT.seat_class_id',
                                                    'GL_STAGE_SEAT.stage_seat_id',
                                                    'GL_STAGE_SEAT.schedule_id',
                                                    'GL_STAGE_SEAT.seat_class_id As stage_seat_class_id',
                                                    'GL_STAGE_SEAT.reserve_code AS stage_reserve_code',
                                                    'GL_GENERAL_RESERVATION.order_id',
                                                    'GL_GENERAL_RESERVATION.reserve_no',
                                                    'GL_GENERAL_RESERVATION.reserve_date',
                                                    'GL_GENERAL_RESERVATION.member_id',
                                                    'GL_GENERAL_RESERVATION.consumer_name',
                                                    'GL_GENERAL_RESERVATION.pay_method',
                                                    'GL_GENERAL_RESERVATION.pickup_method',
                                                    'GL_GENERAL_RESERVATION.mail_address',
                                                    'GL_GENERAL_RESERVATION.tel_num',
                                                    'GL_GENERAL_RESERVATION.cancel_flg',
                                                    'GL_SEAT_SALE.issue_flg',
                                                    'GL_SEAT_SALE.reserve_seq',
                                                    'GL_SEAT_SALE.seat_seq',
                                                    'GL_SEAT_SALE.visit_flg',
                                                    'GL_SEAT_SALE.visit_date'
                                                )
                                            ->leftJoin('GL_STAGE_SEAT', function($join) use ($scheduleId)
                                            {
                                                $join->on('GL_STAGE_SEAT.alloc_seat_id', '=', 'GL_SEAT.alloc_seat_id')
                                                    ->where('GL_STAGE_SEAT.schedule_id', '=',$scheduleId);
                                            })
                                            ->leftJoin('GL_SEAT_SALE', function($join) use ($scheduleId)
                                            {
                                                $join->on('GL_SEAT.alloc_seat_id', '=', 'GL_SEAT_SALE.alloc_seat_id')
                                                    ->where('GL_SEAT_SALE.schedule_id', '=',$scheduleId)
                                                    ->where('GL_SEAT_SALE.seat_status','>',0);
                                                    //  ->where(function($query) {
                                                    //      $query->whereNotNull('order_id')
                                                    //             ->orwhere(function($q2) {
                                                    //                 $q2->whereNull('order_id')
                                                    //                     ->where('temp_reserve_date','>',date('Y-m-d H:i:s', strtotime('+10 min')));
                                                    //             });
                                                    //  });
                                            })
                                            ->leftJoin('GL_GENERAL_RESERVATION', function($join)
                                            {
                                                $join->on('GL_SEAT_SALE.order_id', '=', 'GL_GENERAL_RESERVATION.order_id')
                                                     ->where('GL_GENERAL_RESERVATION.cancel_flg',0);
                                                            // ->where( function($q2){
                                                            //     $q2->where('seat_status',3)
                                                            //         ->orwhere(function($q3){
                                                            //             $q3->where('seat_status',2)
                                                            //                ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', now());
                                                            //         });
                                                            // });
                                            })
                                            ->where('GL_SEAT.performance_id', $performanceId)
                                            ->where(function($e) {
                                                $e->whereNotNull('GL_SEAT.reserve_code')
                                                  ->orWhereNotNull('GL_STAGE_SEAT.reserve_code');
                                            });

            //過濾資料
            if($filterData['filter']){
                //關鍵字
                if($filterData['filterData']['keyword']){
                    $keyword = $filterData['filterData']['keyword'];
                    $result = $result->Where(function($q) use ($keyword){
                                    $q->where('GL_GENERAL_RESERVATION.reserve_no', 'like', '%'.$keyword.'%')
                                    //STS - 2021/6/18 Task 21 - #fix search with 非会員 - START
                                        ->orWhere(function($q2) use ($keyword){
                                            if(!str_contains(trans('sellManage.S_EventDetailNoneMember'), $keyword)){

                                        $q2->where('GL_GENERAL_RESERVATION.member_id', 'like', '%'.$keyword.'%')
                                        ->where('GL_GENERAL_RESERVATION.member_id', '!=', 'gettiis$[N_M]');
                                    }
                                        else $q2->where('GL_GENERAL_RESERVATION.member_id', '=', 'gettiis$[N_M]');

                                      })
                                      //->orWhere('GL_GENERAL_RESERVATION.member_id', 'like', '%'.$keyword.'%')
                                        //STS - 2021/6/18 Task 21 - #fix search with 非会員 - END
                                      ->orWhere('GL_GENERAL_RESERVATION.consumer_name', 'like', '%'.$keyword.'%')
                                      ->orWhere('GL_GENERAL_RESERVATION.mail_address', 'like', '%'.$keyword.'%')
                                      ->orWhere('GL_GENERAL_RESERVATION.tel_num', 'like', '%'.$keyword.'%');
                                });
                }
                //取票方式
                if(!empty($filterData['filterData']['pickup_method']) && !$filterData['filterData']['not_pickup_method']){
                    $result = $result->whereIn('GL_GENERAL_RESERVATION.pickup_method', $filterData['filterData']['pickup_method']);
                }else{
                    $pickup_method = $filterData['filterData']['pickup_method'];
                    $result = $result->Where(function($q) use ($pickup_method){
                        $q->whereIn('GL_GENERAL_RESERVATION.pickup_method',  $pickup_method)
                          ->orWhereNull('GL_GENERAL_RESERVATION.pickup_method');
                    });
                }
                //訂單時間
                if($filterData['filterData']['dateRangeStar'] && $filterData['filterData']['dateRangeEnd']){
                    $result = $result->whereBetween('GL_GENERAL_RESERVATION.reserve_date', [$filterData['filterData']['dateRangeStar'], $filterData['filterData']['dateRangeEnd']]);
                }
                //是否取票
                if(!empty($filterData['filterData']['issue']) && !$filterData['filterData']['noTissue']){
                    $result = $result->whereIn('GL_SEAT_SALE.issue_flg', $filterData['filterData']['issue']);
                }else{
                    $issue  = $filterData['filterData']['issue'];
                    $result = $result->Where(function($q) use ($issue){
                        $q->whereIn('GL_SEAT_SALE.issue_flg',  $issue)
                          ->orWhereNull('GL_SEAT_SALE.issue_flg');
                    });
                }
                //票種
                if($filterData['filterData']['seatType'] !== "all"){
                    $result = $result->where('GL_SEAT_SALE.seat_class_name', '=', $filterData['filterData']['seatType']);
                }
                //票別
                if($filterData['filterData']['ticketType'] !== "all"){
                    $result = $result->where('GL_SEAT_SALE.ticket_class_name', '=', $filterData['filterData']['ticketType']);
                }
            }

            $result = $result->get();
          
            $result = $result->load('seatClass', 'seatClass.ticketClass', 'stageSeats', 'hallSeat', 'hallSeat.floor', 'hallSeat.block', 'reserve')
                             ->toArray();
            return $result;

        }catch(Exception $e){
            Log::info('get schedule data error [getReservationSeatData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 取得自由席資料
     * 
     * @return $result
     */
    public function getFreeSeatReservationData($filterData){
       try{
            $result = $this->GeneralReservationModel->select(
                                                            'GL_GENERAL_RESERVATION.order_id',
                                                            'GL_GENERAL_RESERVATION.reserve_no',
                                                            'GL_GENERAL_RESERVATION.reserve_date',
                                                            'GL_GENERAL_RESERVATION.reserve_expire',
                                                            'GL_GENERAL_RESERVATION.member_id',
                                                            'GL_GENERAL_RESERVATION.consumer_name',
                                                            'GL_GENERAL_RESERVATION.pay_method',
                                                            'GL_GENERAL_RESERVATION.pickup_method',
                                                            'GL_GENERAL_RESERVATION.mail_address',
                                                            'GL_GENERAL_RESERVATION.tel_num',
                                                            'GL_GENERAL_RESERVATION.cancel_flg'
                                                        )
                                                    ->leftJoin('GL_SEAT_SALE', 'GL_SEAT_SALE.order_id', '=', 'GL_GENERAL_RESERVATION.order_id')
                                                    ->whereNull('GL_SEAT_SALE.alloc_seat_id')
                                                    ->whereNull('GL_SEAT_SALE.ticket_class_id')
                                                    ->where('GL_SEAT_SALE.schedule_id', '=', $filterData['scheduleId']);
            if($filterData['filter']){

                //關鍵字
                if($filterData['filterData']['keyword']){
                    $keyword = $filterData['filterData']['keyword'];
                    $result = $result->Where(function($q) use ($keyword){
                                    $q->where('GL_GENERAL_RESERVATION.reserve_no', 'like', '%'.$keyword.'%')
                                    //STS - 2021/6/18 Task 21 - #fix search with 非会員 - START
                                        ->orWhere(function($q2) use ($keyword){
                                            if(!str_contains(trans('sellManage.S_EventDetailNoneMember'), $keyword)){

                                        $q2->where('GL_GENERAL_RESERVATION.member_id', 'like', '%'.$keyword.'%')
                                        ->where('GL_GENERAL_RESERVATION.member_id', '!=', 'gettiis$[N_M]');
                                    }
                                        else $q2->where('GL_GENERAL_RESERVATION.member_id', '=', 'gettiis$[N_M]');

                                      })
                                      //->orWhere('GL_GENERAL_RESERVATION.member_id', 'like', '%'.$keyword.'%')
                                        //STS - 2021/6/18 Task 21 - #fix search with 非会員 - END
                                      ->orWhere('GL_GENERAL_RESERVATION.consumer_name', 'like', '%'.$keyword.'%')
                                      ->orWhere('GL_GENERAL_RESERVATION.mail_address', 'like', '%'.$keyword.'%')
                                      ->orWhere('GL_GENERAL_RESERVATION.tel_num', 'like', '%'.$keyword.'%');
                                });
                }
                //取票方式
                if(!empty($filterData['filterData']['pickup_method'])){
                    $result = $result->whereIn('GL_GENERAL_RESERVATION.pickup_method', $filterData['filterData']['pickup_method']);
                }
                //訂單時間
                if($filterData['filterData']['dateRangeStar'] && $filterData['filterData']['dateRangeEnd']){
                    $result = $result->whereBetween('GL_GENERAL_RESERVATION.reserve_date', [$filterData['filterData']['dateRangeStar'], $filterData['filterData']['dateRangeEnd']]);
                }
                //是否取票
                if(in_array(2, $filterData['filterData']['issue'])){
                    $result = $result->whereIn('GL_SEAT_SALE.issue_flg', $filterData['filterData']['issue']);
                }
                //票種
                if($filterData['filterData']['seatType'] !== "all"){
                    $result = $result->where('GL_SEAT_SALE.seat_class_name', '=', $filterData['filterData']['seatType']);
                }
                //票別
                if($filterData['filterData']['ticketType'] !== "all"){
                    $result = $result->where('GL_SEAT_SALE.ticket_class_name', '=', $filterData['filterData']['ticketType']);
                }
            }
           
            $result = $result->get();

            $result = $result->load(['seatSale' => function ($query) {
                                        $query->whereNull('alloc_seat_id');
                                    }]);
           
            return $result->toArray();
        }catch(Exception $e){
            Log::info('get schedule data error [getGeneralReservationId] :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 取得自由席席位資料
     * 
     * @return general seat sale data
     */
    public function getOrderFreeSeatData($filterData){
        try{
            $result = $this->SeatSaleModel->select(
                                                        'seat_class_name'
                                                    )
                                            ->whereNull('alloc_seat_id')
                                            ->where('order_id', '=', $filterData['order_id'])
                                            ->where('schedule_id', '=', $filterData['scheduleId'])
                                            ->get()
                                            ->toArray();
                                                    
            return $result;
        }catch(Exception $e){
            Log::info('get schedule data error [getGeneralReservationId] :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get Reserve Data
     * 
     */
    public function getReserveData($reserve_code){
      
        $result = $this->ReserveModel->where('reserve_code',  $reserve_code)
                                     ->get();
        
        return $result[0]; 
    }
    /**
     * get seat class data
     * 
     * @param $seat_class_id
     * @return array
     */
    public function getSeatClassData($seat_class_id){
        try{
            $result = $this->SeatClassModel->select(
                                            'GL_SEAT_CLASS.*'
                                        )
                                        ->where('seat_class_id', $seat_class_id)
                                        ->get();

            return $result[0];
        }catch(Exception $e){
            Log::info('error code : 1 | getSeatTypeData function error | data error :'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * 
     * get Seat Sale Data
     * @return general seat sale data
     */
    public function getSeatSaleData($orderId, $scheduleId){
       
        try{
            $resutlt =  $this->SeatSaleModel->select(
                                                '*',
                                                DB::raw('commission_sv +
                                                        commission_payment +
                                                        commission_ticket +
                                                        commission_delivery +
                                                        commission_sub +
                                                        commission_uc
                                                        as seat_commission_sum'),
                                                DB::raw('sale_price +
                                                        commission_sv +
                                                        commission_payment +
                                                        commission_ticket +
                                                        commission_delivery +
                                                        commission_sub +
                                                        commission_uc
                                                        as sale_price_sum')
                                            )
                                            ->where('order_id', $orderId)
                                            ->where('schedule_id', $scheduleId)
                                            ->get();
          
            return $resutlt;

        }catch(Exception $e){
            Log::info('get schedule data error [getSeatSaleData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get performace all data
     * @param
     * @return 
     */
    public function getPerformanceAllData($GLID){
        try{
            $resutlt =  $this->EvenManageModel->where('GLID', $GLID)
                                              ->get();
          
            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getPerformanceAllData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get performance filter data
     * @param
     * @return 
     */
    public function getPerformanceFilterKeyWord($GLID, $keyWord, $status){
        try{
            $keywordResutlt = $this->EvenManageModel->where('GLID', $GLID)
                            ->where('performance_code', 'like', '%'.$keyWord.'%')
                            ->orWhere('performance_name', 'like', '%'.$keyWord.'%')
                            ->orWhere('performance_name_k', 'like', '%'.$keyWord.'%')
                            ->orWhere('performance_name_sub', 'like', '%'.$keyWord.'%')
                            ->orWhere('performance_name_seven', 'like', '%'.$keyWord.'%');
                    
            $resutlt =  $this->EvenManageModel->where('GLID', $GLID)
                            ->whereIn('status', $status)
                            ->union($keywordResutlt)
                            ->get();

            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getPerformanceFilterKeyWord]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get performance filter data
     * @param
     * @return 
     */
    public function getPerformanceFilterData($GLID, $status){
        try{
            $resutlt =  $this->EvenManageModel->where('GLID', $GLID)
                                              ->whereIn('status', $status)
                                              ->get();
          
           
            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getPerformanceFilterData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get  schedule join stagename data
     * @param
     * @return 
     */
    public function getPerformanceData($performance_id){
        try{
            $resutlt =  $this->EvenManageModel->where('performance_id', $performance_id)
                                              ->get();
          
            return $resutlt[0];
        }catch(Exception $e){
            Log::info('get schedule data error [getPerformanceData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get  schedule join stagename data
     * @param
     * @return 
     */
    public function getScheduleData($performance_id){
        try{
            $resutlt =  $this->ScheduleModel->leftJoin('GL_STAGENAME', 'GL_SCHEDULE.stcd', '=', 'GL_STAGENAME.stcd')
                                            ->where('GL_SCHEDULE.performance_id', $performance_id)
                                            ->orderBy('GL_SCHEDULE.performance_date')
                                            ->get();
            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getScheduleData]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get saet total 
     * @param
     * @return 
     */
    public function getSeatTotal($performance_id){
        try{
            $resutlt = $this->SeatModel->leftJoin('GL_STAGE_SEAT', 'GL_SEAT.alloc_seat_id', '=', 'GL_STAGE_SEAT.alloc_seat_id')
                                       ->select(
                                                   'GL_SEAT.*',
                                                   'GL_STAGE_SEAT.alloc_seat_id AS stage_seat_alloc_seat_id',
                                                   'GL_STAGE_SEAT.schedule_id AS stage_seat_schedule_id',
                                                   'GL_STAGE_SEAT.seat_class_id AS stage_seat_seat_class_id',
                                                   'GL_STAGE_SEAT.reserve_code AS stage_seat_reserve_code'
                                                )
                                       ->get();
            
            return $resutlt;
        }catch(Exception $e){
            Log::info('get schedule data error [getSeatTotal]:'.$e->getMessage());
            App::abort(500);
        }
    }
    /**
     * get saet total 
     * @param
     * @return 
     */
    public function getSeatSellData($performance_id, $alloc_seat_id, $schedule_id){
         try{
             $resutlt = $this->SeatModel->leftJoin('GL_STAGE_SEAT', 'GL_SEAT.alloc_seat_id', '=', 'GL_STAGE_SEAT.alloc_seat_id')
                                        ->leftJoin('GL_SEAT_SALE', 'GL_SEAT.alloc_seat_id', '=', 'GL_SEAT_SALE.alloc_seat_id')
                                        ->where('GL_SEAT.alloc_seat_id', $alloc_seat_id)
                                        ->where('GL_SEAT_SALE.schedule_id', $schedule_id)
                                        // ->where('GL_SEAT_SALE.seat_status', '2')
                                        ->Where( function($q) {
                                            $q->Where('GL_SEAT_SALE.seat_status', '1')
                                              ->whereNotNull('GL_SEAT_SALE.order_id')
                                              ->orWhere('GL_SEAT_SALE.seat_status', '2');
                                        })
                                        ->select(
                                                    'GL_SEAT.*',
                                                    'GL_STAGE_SEAT.alloc_seat_id AS stage_seat_alloc_seat_id',
                                                    'GL_STAGE_SEAT.schedule_id AS stage_seat_schedule_id',
                                                    'GL_STAGE_SEAT.seat_class_id AS stage_seat_seat_class_id',
                                                    'GL_STAGE_SEAT.reserve_code AS stage_seat_reserve_code',
                                                    'GL_SEAT_SALE.alloc_seat_id AS seat_sale_alloc_seat_id',
                                                    'GL_SEAT_SALE.seat_sale_id AS seat_sale_seat_sale_id',
                                                    'GL_SEAT_SALE.sale_price AS seat_sale_seat_sale_price',
                                                    'GL_SEAT_SALE.commission_sv AS seat_sale_commission_sv',
                                                    'GL_SEAT_SALE.commission_payment AS seat_sale_commission_payment',
                                                    'GL_SEAT_SALE.commission_ticket AS seat_sale_commission_ticket',
                                                    'GL_SEAT_SALE.commission_delivery AS seat_sale_commission_delivery',
                                                    'GL_SEAT_SALE.commission_sub AS seat_sale_commission_sub',
                                                    'GL_SEAT_SALE.commission_uc AS seat_sale_commission_uc'
                                                 )
                                        ->get();
            
             return $resutlt;
         }catch(Exception $e){
             Log::info('get schedule data error [getSeatSellData]:'.$e->getMessage());
             App::abort(500);
         }
    }
    /**
     * get saet total 
     * @param
     * @return 
     */
    public function getPerformanceSellInfo($GLID, $admin_flg, $keyword, $filterStatus, $performance_status_delete){
         try{
               //subquery GL_NONRESERVED_STOCK free seat
               $freeSeat = DB::table('GL_PERFORMANCE')->join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                                      ->join('GL_NONRESERVED_STOCK','GL_SCHEDULE.schedule_id','=','GL_NONRESERVED_STOCK.schedule_id')
                                                      ->select('GL_PERFORMANCE.performance_id'
                                                         , DB::raw('sum(GL_NONRESERVED_STOCK.stock_limit) as free_cnt')
                                                          )
                                                  ->groupBy('GL_PERFORMANCE.performance_id'); 

                if($admin_flg){                                 
                    $freeSeat = $freeSeat->where('GL_PERFORMANCE.GLID', $GLID);
                }

               //subquery GL_SEAT non reserved
               $seat = DB::table('GL_SEAT')->join('GL_SCHEDULE','GL_SEAT.performance_id','=','GL_SCHEDULE.performance_id')
                                           ->join('GL_PERFORMANCE','GL_SEAT.performance_id','=','GL_PERFORMANCE.performance_id')
                                           ->join('GL_HALL_SEAT','GL_HALL_SEAT.seat_id','=','GL_SEAT.seat_id')
                                           ->whereNotNull('GL_SEAT.seat_class_id')
                                           ->whereRaw('GL_HALL_SEAT.profile_id = GL_PERFORMANCE.seatmap_profile_cd')
                                           ->select('GL_SEAT.performance_id'
                                                   , DB::raw('count(GL_SEAT.performance_id) as seat_cnt')
                                                    )
                                           ->groupBy('GL_SEAT.performance_id');

                if($admin_flg){                                 
                    $seat = $seat->where('GL_PERFORMANCE.GLID', $GLID);
                }

 
           
                //GL_V_Seat_Static_of_Stage
                $seatReserved = DB::table('GL_PERFORMANCE')->join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                                          ->join('GL_V_Seat_Static_of_Stage','GL_SCHEDULE.schedule_id','=','GL_V_Seat_Static_of_Stage.schedule_id')
                                                          ->select('GL_PERFORMANCE.performance_id',
                                                                    DB::raw('sum(RES) as seat_cnt'),
                                                                    DB::raw('sum(UNSET) as UNSET'),
                                                                    DB::raw('sum(stock_limit) as stock_limit'),
                                                                    DB::raw('sum(SALE) as SALE')
                                                                   )
                                                          ->groupBy('GL_PERFORMANCE.performance_id');
                                                         
                if($admin_flg){                                 
                    $seatReserved = $seatReserved->where('GL_PERFORMANCE.GLID', $GLID);
                }

                //取得訂單編號
                $order_list = DB::table('GL_PERFORMANCE')
                                ->join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                ->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                ->whereIn('GL_SEAT_SALE.seat_status', [3])
                                ->select(
                                    'GL_PERFORMANCE.performance_id',
                                    'GL_SEAT_SALE.order_id'
                                )
                                ->groupBy(
                                    'GL_SEAT_SALE.order_id',
                                    'GL_PERFORMANCE.performance_id'
                                );
                                       
                //取得每張訂單票價總額
                $order_seat_price = DB::table('GL_PERFORMANCE')
                                        ->join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                        ->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                        ->whereIn('GL_SEAT_SALE.seat_status', [3])
                                        ->select(
                                            'GL_SEAT_SALE.order_id',
                                             DB::raw('sum(GL_SEAT_SALE.sale_price+
                                                                    GL_SEAT_SALE.commission_sv +
                                                                    GL_SEAT_SALE.commission_payment +
                                                                    GL_SEAT_SALE.commission_ticket +
                                                                    GL_SEAT_SALE.commission_delivery +
                                                                    GL_SEAT_SALE.commission_sub +
                                                                    GL_SEAT_SALE.commission_uc 
                                                            ) as sale_price'
                                                    )
                                        )
                                        ->groupBy('GL_SEAT_SALE.order_id');    
                                        
               //subquery GL_SEAT_SALE sale_price
               $seatSale= DB::table('GL_PERFORMANCE')->join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                                     ->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                     ->whereNotNull('GL_SEAT_SALE.order_id')
                                                     ->whereIn('GL_SEAT_SALE.seat_status', [3])
                                                     ->select('GL_PERFORMANCE.performance_id'
                                                             ,DB::raw('sum(GL_SEAT_SALE.sale_price) as sale_price')
                                                             )
                                                     ->groupBy('GL_PERFORMANCE.performance_id');

                if($admin_flg){                                 
                    $seatSale = $seatSale->where('GL_PERFORMANCE.GLID', $GLID);
                }
                
               //subquery GL_SEAT_SALE sale_count
               $seatSaleCnt= DB::table('GL_PERFORMANCE')->join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                                        ->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                        ->select('GL_PERFORMANCE.performance_id'
                                                                ,DB::raw('count(GL_SEAT_SALE.schedule_id) as sale_cnt')
                                                                )
                                                        ->groupBy('GL_PERFORMANCE.performance_id');
                if($admin_flg){                                 
                    $seatSaleCnt = $seatSaleCnt->where('GL_PERFORMANCE.GLID', $GLID);
                }
                $seatSaleCnt = $seatSaleCnt->whereNotNull('GL_SEAT_SALE.order_id');

               $result =  $this->EvenManageModel->leftJoin(DB::raw('('. $freeSeat->toSql() .') as GL_FREE_SEAT'), 'GL_FREE_SEAT.performance_id', '=', 'GL_PERFORMANCE.performance_id')
                                                ->mergeBindings($freeSeat)
                                                ->leftJoin(DB::raw('('. $seat->toSql() .') as GL_SEAT'), 'GL_SEAT.performance_id', '=', 'GL_PERFORMANCE.performance_id')
                                                ->mergeBindings($seat)
                                                ->leftJoin(DB::raw('('. $seatReserved->toSql() .') as GL_SEAT_RESERVED'), 'GL_SEAT_RESERVED.performance_id', '=', 'GL_PERFORMANCE.performance_id')
                                                ->mergeBindings($seatReserved)
                                                ->leftJoin(DB::raw('('. $seatSale->toSql() .') as GL_SEAT_SALE'), 'GL_SEAT_SALE.performance_id', '=', 'GL_PERFORMANCE.performance_id')
                                                ->mergeBindings($seatSale)
                                                ->leftJoin(DB::raw('('. $seatSaleCnt->toSql() .') as GL_SEAT_SALE_count'), 'GL_SEAT_SALE_count.performance_id', '=', 'GL_PERFORMANCE.performance_id')
                                                ->mergeBindings($seatSaleCnt)
                                                ->leftJoin('GL_DRAFT','GL_PERFORMANCE.performance_id','=','GL_DRAFT.performance_id')
                                                ->orderBy('GL_PERFORMANCE.performance_id', 'desc')
                                                ->select('GL_PERFORMANCE.performance_id'
                                                        ,'GL_PERFORMANCE.status'
                                                        ,'GL_PERFORMANCE.performance_name','GL_SEAT.seat_cnt'
                                                        ,DB::raw('nvl(GL_FREE_SEAT.free_cnt,0)    as free_cnt')
                                                        ,DB::raw('nvl(GL_SEAT.seat_cnt,0)                    as seat_cnt')
                                                        ,DB::raw('nvl(GL_SEAT_RESERVED.seat_cnt,0)           as seat_cnt_reserved')
                                                        ,DB::raw('truncate(nvl(GL_SEAT_SALE.sale_price,0),0) as o_sale_price')
                                                        ,DB::raw('truncate(nvl(GL_SEAT_SALE.sale_price,0),0) as sale_price')
                                                    ,DB::raw('nvl(GL_SEAT_SALE_count.sale_cnt,0)         as sale_cnt')
                                                        ,DB::raw('TIMESTAMPDIFF(DAY,GL_PERFORMANCE.performance_st_dt,GL_PERFORMANCE.performance_end_dt)+1 as days')
                                                        ,'GL_PERFORMANCE.sch_kbn'
                                                        ,'GL_PERFORMANCE.announce_date'                                                        
                                                    ,DB::raw('GL_DRAFT.draft_info as temporary_info')
                                                );
                if($admin_flg){                                 
                    $result = $result->where('GL_PERFORMANCE.GLID', $GLID);
                }

               if(!empty($keyword))
               {
                  $result = $result->where(function($query) use ($keyword)
                                    {
                                      $query->where('GL_PERFORMANCE.performance_code', 'like', "%{$keyword}%")
                                            ->orWhere('GL_PERFORMANCE.performance_name', 'like', "%{$keyword}%")
                                            ->orWhere('GL_PERFORMANCE.performance_name_k', 'like', "%{$keyword}%")
                                            ->orWhere('GL_PERFORMANCE.performance_name_sub', 'like', "%{$keyword}%")
                                            ->orWhere('GL_PERFORMANCE.performance_name_seven', 'like', "%{$keyword}%");
                                    });
               }
             
                $result = $result->whereNotIn('GL_PERFORMANCE.status', [0, 1, 2, $performance_status_delete])
               
                //$result = $result->where('GL_PERFORMANCE.trans_flg', '>', \Config::get('constant.GETTIIS_trans.yet')) removed by LST redmine#1024
                                 ->get(); 
  
            return $result;
         }catch(Exception $e){
             Log::info('getPerformanceSellInfo error :'.$e->getMessage());
             throw new Exception ('getPerformanceSellInfo error :'.$e->getMessage());
         }
    }
    /**
     * get seat change number by stage 
     * @param
     * @return 
     */
    public function getStageChangeSeatCount($scheduleId){
        try {
            $class_change = 0;
            $reserve_change = 0;
            $ret = DB::table('GL_STAGE_SEAT')->join('GL_SEAT','GL_STAGE_SEAT.alloc_seat_id','=','GL_SEAT.alloc_seat_id')
                                                ->select(
                                                    'GL_STAGE_SEAT.alloc_seat_id',
                                                    'GL_SEAT.seat_class_id as old_seat_class',
                                                    'GL_STAGE_SEAT.seat_class_id as new_seat_class',
                                                    'GL_SEAT.reserve_code as old_reserve',
                                                    'GL_STAGE_SEAT.reserve_code as new_reserve')
                                                ->where('GL_STAGE_SEAT.schedule_id', $scheduleId)
                                                ->get(); 
            
            foreach($ret as $seat) {

                if($seat->old_seat_class) {
                    if(!$seat->new_seat_class) {
                        $class_change--;
                    }
                }
                else {
                    if($seat->new_seat_class) {
                        $class_change++;
                    }
                }

                if($seat->old_reserve) {
                    if(!$seat->new_reserve) {
                        $reserve_change--;
                    }
                }
                else {
                    if($seat->new_reserve) {
                        $reserve_change++;
                    }
                }
            }
            
            return [$class_change,$reserve_change];
        }
        catch(Exception $e){
            Log::info('getStageChangeSeatCount error :'.$e->getMessage());
            throw new Exception ('getStageChangeSeatCount error :'.$e->getMessage());
        }
    }

    /**
     * get saet total 
     * @param
     * @return 
     */
    public function getStageData($performanceId){
         try{
               //subquery GL_NONRESERVED_STOCK free seat
               $freeSeat = DB::table('GL_SCHEDULE')->join('GL_NONRESERVED_STOCK','GL_SCHEDULE.schedule_id','=','GL_NONRESERVED_STOCK.schedule_id')
                                                   ->where('GL_SCHEDULE.performance_id', $performanceId)
                                                   ->select('GL_SCHEDULE.schedule_id'
                                                          , DB::raw('sum(GL_NONRESERVED_STOCK.stock_limit) as free_cnt')
                                                           )
                                                  ->groupBy('GL_SCHEDULE.schedule_id');              
               //subquery GL_SEAT non reserved
               $seat = DB::table('GL_SEAT')->join('GL_SCHEDULE','GL_SEAT.performance_id','=','GL_SCHEDULE.performance_id')
                                            ->join('GL_HALL_SEAT','GL_HALL_SEAT.seat_id','=','GL_SEAT.seat_id')
                                            ->join('GL_PERFORMANCE','GL_PERFORMANCE.performance_id','=','GL_SEAT.performance_id')
                                           ->where('GL_SCHEDULE.performance_id', $performanceId)
                                           ->whereNotNull('GL_SEAT.seat_class_id')
                                           ->whereRaw('GL_HALL_SEAT.profile_id = GL_PERFORMANCE.seatmap_profile_cd')
                                           ->select('GL_SCHEDULE.schedule_id'
                                                   , DB::raw('count(1) as seat_cnt')
                                                    )
                                           ->groupBy('GL_SCHEDULE.schedule_id');

               //subquery GL_SEAT reserved
               $seatReserved = DB::table('GL_SEAT')->join('GL_SCHEDULE','GL_SEAT.performance_id','=','GL_SCHEDULE.performance_id')
                                                    ->join('GL_HALL_SEAT','GL_HALL_SEAT.seat_id','=','GL_SEAT.seat_id')
                                                    ->join('GL_PERFORMANCE','GL_PERFORMANCE.performance_id','=','GL_SEAT.performance_id')
                                                   ->where('GL_SCHEDULE.performance_id', $performanceId)
                                                   ->whereNotNull('GL_SEAT.reserve_code')
                                                   ->whereRaw('GL_HALL_SEAT.profile_id = GL_PERFORMANCE.seatmap_profile_cd')
                                                   ->select('GL_SCHEDULE.schedule_id'
                                                           ,DB::raw('count(1) as seat_cnt')
                                                           )
                                                   ->groupBy('GL_SCHEDULE.schedule_id');

                // //取得訂單編號
                // $order_list = DB::table('GL_SCHEDULE')
                //                 ->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                //                 ->where('GL_SCHEDULE.performance_id', $performanceId)
                //                 ->whereIn('GL_SEAT_SALE.seat_status', [3])
                //                 ->select(
                //                     'GL_SEAT_SALE.order_id','GL_SCHEDULE.schedule_id'
                //                 )
                //                 ->groupBy('GL_SEAT_SALE.order_id','GL_SCHEDULE.schedule_id');

                // //取得每張訂單票價總額
                // $order_seat_price = DB::table('GL_SCHEDULE')->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                //                         ->where('GL_SCHEDULE.performance_id', $performanceId)
                //                         ->whereIn('GL_SEAT_SALE.seat_status', [3])
                //                         ->select(
                //                             'GL_SEAT_SALE.order_id',
                //                             DB::raw('sum(GL_SEAT_SALE.sale_price) as sale_price')
                //                         )
                //                         ->groupBy('GL_SEAT_SALE.order_id');
              
                // //取得已修改金額與原金額的差額
                // $amount_total_difference = DB::table( DB::raw("({$order_list->toSql()}) as order_list") ) 
                //                             ->mergeBindings($order_list)
                //                             ->join('GL_AMOUNT_REVISE','order_list.order_id','=','GL_AMOUNT_REVISE.order_id')
                //                             ->leftJoin(DB::raw('('. $order_seat_price->toSql() .') as order_seat_price'), 'order_list.order_id', '=', 'order_seat_price.order_id')
                //                             ->mergeBindings($order_seat_price)
                //                             ->select(
                //                                 'order_list.schedule_id',DB::raw('order_list.order_id as order_id'),
                //                                 DB::raw('sum(order_seat_price.sale_price) - sum(GL_AMOUNT_REVISE.amount_total) as amount_difference') 
                //                             )
                //                             ->groupBy('order_list.order_id','order_list.schedule_id');
              
               //subquery GL_SEAT_SALE sale_price
               $seatSale= DB::table('GL_SCHEDULE')->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                  ->where('GL_SCHEDULE.performance_id', $performanceId)
                                                  ->whereNotNull('GL_SEAT_SALE.order_id')
                                                  ->whereIn('GL_SEAT_SALE.seat_status', [3])
                                                  ->select('GL_SCHEDULE.schedule_id',
                                                            DB::raw('sum(GL_SEAT_SALE.sale_price+
                                                                         GL_SEAT_SALE.commission_sv +
                                                                         GL_SEAT_SALE.commission_payment +
                                                                         GL_SEAT_SALE.commission_ticket +
                                                                         GL_SEAT_SALE.commission_delivery +
                                                                         GL_SEAT_SALE.commission_sub +
                                                                         GL_SEAT_SALE.commission_uc 
                                                                    ) as sale_price')
                                                           )
                                                  ->groupBy('GL_SCHEDULE.schedule_id');
                                                 // +
                                                //   GL_SEAT_SALE.commission_sv +
                                                //   GL_SEAT_SALE.commission_payment +
                                                //   GL_SEAT_SALE.commission_ticket +
                                                //   GL_SEAT_SALE.commission_delivery +
                                                //   GL_SEAT_SALE.commission_sub +
                                                //   GL_SEAT_SALE.commission_uc         
               //subquery GL_SEAT_SALE sale_count
               $seatSaleCnt= DB::table('GL_SCHEDULE')->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                     ->where('GL_SCHEDULE.performance_id', $performanceId)
                                                     ->whereIn('GL_SEAT_SALE.seat_status', [3])
                                                     ->select('GL_SCHEDULE.schedule_id'
                                                              ,DB::raw('count(GL_SEAT_SALE.schedule_id) as sale_cnt')
                                                              )
                                                     ->groupBy('GL_SCHEDULE.schedule_id');

               $seatOnProcessCnt= DB::table('GL_SCHEDULE')->join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                     ->join('GL_GENERAL_RESERVATION','GL_GENERAL_RESERVATION.order_id','=','GL_SEAT_SALE.order_id')
                                                     ->where('GL_SCHEDULE.performance_id', $performanceId)
                                                     //->where('GL_GENERAL_RESERVATION.reserve_expire', '>' ,now()) 20201027 Kei.O 未使用メソッドだが念のため
                                                    //  ->where(DB::raw('GL_GENERAL_RESERVATION.reserve_expire + interval 5 hour'), '>', now())
                                                     ->whereIn('GL_SEAT_SALE.seat_status', [2])
                                                     ->select('GL_SCHEDULE.schedule_id'
                                                              ,DB::raw('count(GL_SEAT_SALE.schedule_id) as sale_onProcess')
                                                              )
                                                     ->groupBy('GL_SCHEDULE.schedule_id');

                                                     
               $result =  $this->EvenManageModel->leftJoin('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                                ->leftJoin('GL_STAGENAME','GL_SCHEDULE.stcd','=','GL_STAGENAME.stcd')
                                                ->leftJoin(DB::raw('('. $freeSeat->toSql() .') as GL_FREE_SEAT'), 'GL_FREE_SEAT.schedule_id', '=', 'GL_SCHEDULE.schedule_id')
                                                ->mergeBindings($freeSeat)
                                                ->leftJoin(DB::raw('('. $seat->toSql() .') as GL_SEAT'), 'GL_SEAT.schedule_id', '=', 'GL_SCHEDULE.schedule_id')
                                                ->mergeBindings($seat)
                                                ->leftJoin(DB::raw('('. $seatReserved->toSql() .') as GL_SEAT_RESERVED'), 'GL_SEAT_RESERVED.schedule_id', '=', 'GL_SCHEDULE.schedule_id')
                                                ->mergeBindings($seatReserved)
                                                ->leftJoin(DB::raw('('. $seatSale->toSql() .') as GL_SEAT_SALE'), 'GL_SEAT_SALE.schedule_id', '=', 'GL_SCHEDULE.schedule_id')
                                                ->mergeBindings($seatSale)
                                                ->leftJoin(DB::raw('('. $seatSaleCnt->toSql() .') as GL_SEAT_SALE_count'), 'GL_SEAT_SALE_count.schedule_id', '=', 'GL_SCHEDULE.schedule_id')
                                                ->mergeBindings($seatSaleCnt)
                                                ->leftJoin(DB::raw('('. $seatOnProcessCnt->toSql() .') as GL_SEAT_SALE_onProcess'), 'GL_SEAT_SALE_onProcess.schedule_id', '=', 'GL_SCHEDULE.schedule_id')
                                                ->mergeBindings($seatOnProcessCnt)
                                                // ->leftJoin(DB::raw('('. $amount_total_difference->toSql() .') as amount_total_difference'), 'GL_SCHEDULE.schedule_id', '=', 'amount_total_difference.schedule_id')
                                                // ->mergeBindings($amount_total_difference)
                                                ->leftJoin('GL_DRAFT','GL_PERFORMANCE.performance_id','=','GL_DRAFT.performance_id')
                                                ->where('GL_PERFORMANCE.performance_id', $performanceId)
                                                ->select('GL_PERFORMANCE.performance_id'
                                                       , 'GL_PERFORMANCE.status'
                                                       , 'GL_PERFORMANCE.performance_name'
                                                       , 'GL_PERFORMANCE.performance_name_sub'  
                                                       , 'GL_PERFORMANCE.sch_kbn'
                                                       ,  DB::raw('GL_DRAFT.draft_info as temporary_info')
                                                       , 'GL_PERFORMANCE.performance_st_dt'
                                                       , 'GL_PERFORMANCE.performance_end_dt'
                                                       , 'GL_PERFORMANCE.seatmap_profile_cd'
                                                       ,'GL_PERFORMANCE.announce_date'    
                                                       , 'GL_SCHEDULE.schedule_id'
                                                       , 'GL_SCHEDULE.performance_date'
                                                       , 'GL_STAGENAME.stage_name'
                                                       //, DB::raw('nvl(amount_difference,0) as amount_difference')
                                                       , DB::raw('truncate(nvl(GL_SEAT_SALE.sale_price,0),0) as o_sale_price')
                                                       , DB::raw('TIME_FORMAT(GL_SCHEDULE.start_time, "%H:%i") as start_time')
                                                       , DB::raw('nvl(GL_SCHEDULE.cancel_flg,0) as cancel_flg')
                                                       , DB::raw('nvl(GL_FREE_SEAT.free_cnt,0)               as free_cnt')
                                                   , DB::raw('nvl(GL_SEAT.seat_cnt,0)                    as seat_cnt')
                                                   , DB::raw('nvl(GL_SEAT_RESERVED.seat_cnt,0)           as seat_cnt_reserved')
                                                   , DB::raw('truncate(nvl(GL_SEAT_SALE.sale_price,0),0) as sale_price')
                                                   , DB::raw('nvl(GL_SEAT_SALE_count.sale_cnt,0)         as sale_cnt')
                                                   , DB::raw('nvl(GL_SEAT_SALE_onProcess.sale_onProcess,0)         as sale_onProcess')
                                                   
                                                       , DB::raw('TIMESTAMPDIFF(DAY,GL_PERFORMANCE.performance_st_dt,GL_PERFORMANCE.performance_end_dt)+1 as days')
                                                         )                                                
                                                ->get(); 
            
                                               
             return $result;
         }catch(Exception $e){
             Log::info('getStageData error :'.$e->getMessage());
             throw new Exception ('getStageData error :'.$e->getMessage());
         }
    }
    /**
     * getTicketSoldPriceCard 
     * @param $id,$performanceId,$dateFrom,$dateTo,$payMethod
     * @return $result
     */
    public function performanceListForReport($filterData){    
        
        try{
            //期間内に入金、キャンセルの有った期間を取得  
            $result =  $this->EvenManageModel->Join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                             ->Join('GL_SALES_TERM as EARLY_SALES','GL_PERFORMANCE.performance_id','=','EARLY_SALES.performance_id')        
                                             ->Join('GL_SALES_TERM as NORMAL_SALES','GL_PERFORMANCE.performance_id','=','NORMAL_SALES.performance_id')        
                                             ->Join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                             ->where('GL_PERFORMANCE.GLID', $filterData['GLID'])
                                             ->where('EARLY_SALES.sales_kbn', '=',  \Config::get('constant.ticket_sales_kbn.early')) //先行販売
                                             ->where('NORMAL_SALES.sales_kbn', '=',  \Config::get('constant.ticket_sales_kbn.normal')) //一般販売
                                             ->where('GL_SEAT_SALE.payment_date', '>=', $filterData['startdt'])
                                             ->where('GL_SEAT_SALE.payment_date', '<=', $filterData['enddt'])
                                             ->select(DB::raw('distinct GL_PERFORMANCE.performance_id')
                                                                      ,'GL_PERFORMANCE.performance_code'
                                                                      ,'GL_PERFORMANCE.status'
                                                                      ,'GL_PERFORMANCE.performance_name'
                                                                      ,'GL_PERFORMANCE.performance_st_dt'
                                                                      ,'GL_PERFORMANCE.performance_end_dt'
                                                                      ,'GL_PERFORMANCE.trans_flg'
                                                                      ,'GL_PERFORMANCE.sale_type'
                                                                      ,'EARLY_SALES.reserve_st_date as early_bird_st'                                                     
                                                                      ,'NORMAL_SALES.reserve_st_date as normal_st'                                                     
                                                     )
                                             ->union
                                               (
                                                //キャンセル
                                                $this->EvenManageModel->Join('GL_SCHEDULE','GL_PERFORMANCE.performance_id','=','GL_SCHEDULE.performance_id')
                                                                      ->Join('GL_SALES_TERM as EARLY_SALES','GL_PERFORMANCE.performance_id','=','EARLY_SALES.performance_id')        
                                                                      ->Join('GL_SALES_TERM as NORMAL_SALES','GL_PERFORMANCE.performance_id','=','NORMAL_SALES.performance_id')        
                                                                      ->Join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                                      ->Join('GL_CANCEL_ORDER','GL_SEAT_SALE.order_id','=','GL_CANCEL_ORDER.order_id')
                                                                      ->where('GL_PERFORMANCE.GLID', $filterData['GLID'])
                                                                      ->where('EARLY_SALES.sales_kbn', '=',  \Config::get('constant.ticket_sales_kbn.early')) //先行販売
                                                                      ->where('NORMAL_SALES.sales_kbn', '=',  \Config::get('constant.ticket_sales_kbn.normal')) //一般販売
                                                                      ->where('GL_CANCEL_ORDER.created_at', '>=', $filterData['startdt'])
                                                                      ->where('GL_CANCEL_ORDER.created_at', '<=', $filterData['enddt'])
                                                                      ->select(DB::raw('distinct GL_PERFORMANCE.performance_id')
                                                                                               ,'GL_PERFORMANCE.performance_code'
                                                                                               ,'GL_PERFORMANCE.status'
                                                                                               ,'GL_PERFORMANCE.performance_name'
                                                                                               ,'GL_PERFORMANCE.performance_st_dt'
                                                                                               ,'GL_PERFORMANCE.performance_end_dt'
                                                                                               ,'GL_PERFORMANCE.trans_flg'
                                                                                               ,'GL_PERFORMANCE.sale_type'
                                                                                               ,'EARLY_SALES.reserve_st_date'   
                                                                                               ,'NORMAL_SALES.reserve_st_date as normal_st'                                                     
                                                                              )
                                               )
                                             ->get();
            
            foreach($result as $preformance)
            {   
              //販売期間
              $earlyBirdDateStart = isset($preformance['early_bird_st']) ? $preformance['early_bird_st'] : ""; //先行from
              $normalDateStart    = isset($preformance['normal_st']) ? $preformance['normal_st'] : "";    //一般from
              if (!empty($normalDateStart)) $starDate = $normalDateStart;
              if (!empty($earlyBirdDateStart)) $starDate = $earlyBirdDateStart;
                
              $status_data = array(
                                    'status'    => $preformance['status'],
                                    'star_date' => $starDate,
                                    'performance_st_dt' => $preformance['performance_st_dt'],
                                    'performance_end_dt' =>  $preformance['performance_end_dt'],
                                    'sale_type' => $preformance['sale_type'],
                                    'trans_flg' => $preformance['trans_flg'],
                                   );

              $disp_status = $this->getPerformanceDispStatus($status_data);
              $preformance['status'] = $disp_status;            
            }
        }catch(Exception $e){
            Log::info('performanceListForReport error :'.$e->getMessage());
            throw new Exception ('performanceListForReport error :'.$e->getMessage());
        }     
        return $result;
    }    
    /**
     * performanceDetailForReport 
     * @param $performanceId
     * @return $result
     */
    public function performanceDetailForReport($performanceId){    
        
        try{
          $result =  $this->EvenManageModel->whereIn('performance_id', $performanceId)
                                           ->select(
                                                    'performance_id'
                                                   ,'performance_code'
                                                   ,'performance_name'
                                                   ,'hall_disp_name'
                                                   ,DB::raw('DATE_FORMAT(performance_st_dt,"%Y/%m/%d") as performance_st_dt')
                                                   ,DB::raw('CASE dayofweek(performance_st_dt)
                                                                     WHEN 1 THEN "（日）"
                                                                     WHEN 2 THEN "（月）"
                                                                     WHEN 3 THEN "（火）"
                                                                     WHEN 4 THEN "（水）"
                                                                     WHEN 5 THEN "（木）"
                                                                     WHEN 6 THEN "（金）"
                                                                     WHEN 7 THEN "（土）"
                                                                   END as day_st')
                                                   ,DB::raw('DATE_FORMAT(performance_end_dt,"%Y/%m/%d") as performance_end_dt')
                                                   ,DB::raw('CASE dayofweek(performance_end_dt)
                                                                     WHEN 1 THEN "（日）"
                                                                     WHEN 2 THEN "（月）"
                                                                     WHEN 3 THEN "（火）"
                                                                     WHEN 4 THEN "（水）"
                                                                     WHEN 5 THEN "（木）"
                                                                     WHEN 6 THEN "（金）"
                                                                     WHEN 7 THEN "（土）"
                                                             END as day_end')
                                                   )
                                           ->orderby('performance_code')
                                           ->get(); 
        }catch(Exception $e){
            Log::info('performanceDetailForReport error :'.$e->getMessage());
            throw new Exception ('performanceDetailForReport error :'.$e->getMessage());
        }     
        return $result;
    }        
    /**
     * getTicketSoldPriceCard 
     * @param $id,$performanceId,$dateFrom,$dateTo,$payMethod
     * @return $ticketPriceList
     */
    public function getTicketSoldPrice($GLID,$id,$performanceId,$dateFrom,$dateTo,$payMethod){    
        //期間内に支払いの有った支払い方法がクレジット/セブンのレコードの集計
        try{
            //【システム精算明細書】クレジットカード受領代金、セブン代理受領代金-チケット代金（件数、枚数、金額）
            //                      セブンイレブン手数料-発券手数料、代理受領手数料    
            $SoldInfo = $this->makeQueryGetSalesPriceFromPerfomanceId($performanceId,$dateFrom,$dateTo,$payMethod)
                             ->select(
                                      'GL_SCHEDULE.performance_id'
                                     ,DB::raw('count(distinct GL_GENERAL_RESERVATION.order_id) as reserve_num') //件数
                                     ,DB::raw('count(GL_SEAT_SALE.order_id) as seats_num')                      //枚数
                                     ,DB::raw('truncate(sum(GL_SEAT_SALE.sale_price),0) as sale_price')         //金額
                                     )
                             ->groupby('GL_SCHEDULE.performance_id');

            $commissionInfo = array();
            if($payMethod == \Config::get('constant.pay_method.store'))
            {/*
              //セブンイレブン手数料
              $SoldInfo = $SoldInfo->addSelect(
                                               'GL_SEAT_SALE.payment_date'                                   
                                              )
                                   ->groupby('GL_SEAT_SALE.payment_date')
                                   ->get();
              */
              $SoldInfo = $SoldInfo->addSelect(
                                               DB::raw($this->makeQueryGetRateAmount('GL_GENERAL_RESERVATION.GLID'  ,\Config::get('constant.client_commission.seven_payment'),'GL_SEAT_SALE.payment_date',false) . 'as payment_amount')  //決済手数料
                                              ,DB::raw($this->makeQueryGetApplyPeriod('GL_GENERAL_RESERVATION.GLID' ,\Config::get('constant.client_commission.seven_payment'),'GL_SEAT_SALE.payment_date')  . 'as payment_apply_date ')  //決済手数料摘要日
                                              ,DB::raw($this->makeQueryGetRateAmount('GL_GENERAL_RESERVATION.GLID'  ,\Config::get('constant.client_commission.seven_pickup'),'GL_SEAT_SALE.payment_date',false) . 'as pickup_amount')   //発券手数料
                                              ,DB::raw($this->makeQueryGetApplyPeriod('GL_GENERAL_RESERVATION.GLID' ,\Config::get('constant.client_commission.seven_pickup'),'GL_SEAT_SALE.payment_date')  . 'as pickup_apply_date ')   //発券手数料摘要日
                                              ,DB::raw($this->makeQueryGetTaxRate('GL_SEAT_SALE.payment_date') . 'as tax') //税率 
                                              )
                                   ->groupby('GL_GENERAL_RESERVATION.GLID','GL_SEAT_SALE.payment_date')
                                   ->get();
/*                                    
              //手数料、税率取得
              $CommissionInfo = $this->CommissionClientModel
                                     ->select(
                                              'commission_type'
                                             ,'valid_period'
                                             ,'rate'
                                             ,'amount'
                                             )
                                     ->where('GLID',1)
                                     ->where('commission_type',0)
                                     ->where('valid_period', '>=' , $dateFrom)      
                                     ->union
                                       (
                                        $this->CommissionClientModel
                                             ->select(
                                                      'commission_type'
                                                     ,'valid_period'
                                                     ,'rate'
                                                     ,'amount'
                                                     )
                                             ->where('GLID',$GLID)
                                             ->whereIn('commission_type',[3,4])
                                             ->where('valid_period', '>=' , $dateFrom)         
                                       )
                                     ->orderby('commission_type','valid_period')
                                     ->get();
              
              $tax     = array(); //税率
              $paynent = array(); //決済手数料
              $pickup  = array(); //発券手数
              foreach( $CommissionInfo as $comInfo)
              {
                //todo const
                if($comInfo->commission_type == 0)
                {
                  //税率
                  $tax[] = array(
                                 "valid_period" => $comInfo->valid_period,
                                 "rate"         => $comInfo->rate,
                                );   
                }elseif($comInfo->commission_type == 3){
                  //決済手数料
                  $paynent[] = array(
                                     "valid_period" => $comInfo->valid_period,
                                     "amount"       => $comInfo->amount,
                                     );
                }elseif($comInfo->commission_type == 4){
                  //発券手数料
                  $pickup[] = array(
                                     "valid_period" => $comInfo->valid_period,
                                     "amount"       => $comInfo->amount,
                                     );                    
                }
              }
              $commissionInfo = array(
                                       'tax'     => $tax     //税率
                                      ,'paynent' => $paynent //決済手数料
                                      ,'pickup'  => $pickup  //発券手数
                                      );
 */ 
           }else{
              $SoldInfo = $SoldInfo->get();
            }                  

            foreach( $SoldInfo as $ticketPrice)
            {
              $this->SysReportModel->insert([
                                             'id' => $id
                                             ,'commission_type' => ($payMethod == \Config::get('constant.pay_method.card')) ? \Config::get('constant.sysrep_comtype.card_receipt') : \Config::get('constant.sysrep_comtype.seven_receipt') //精算区分 1:クレジットカード受領代金 2:セブン代理
                                             ,'performance_id'  => $ticketPrice->performance_id                   //公演id
                                             ,'payment_type'    => \Config::get('constant.sysrep_paytype.ticket') //金額区分 1:チケット代金
                                             ,'unit_price'      => 0                            //単価
                                             ,'number'          => $ticketPrice->reserve_num    //件数
                                             ,'sheets_number'   => $ticketPrice->seats_num      //枚数
                                             ,'amount'          => $ticketPrice->sale_price     //金額,
                                            ]);
             
              if($payMethod == \Config::get('constant.pay_method.store'))
              {
                //セブンイレブン手数料  
                //発券手数料 = 枚数 * round(発券手数料 * 税率)
                $ticketCommission = $ticketPrice->seats_num  * round($ticketPrice->tax * $ticketPrice->pickup_amount);
                $this->SysReportModel->insert([
                                               'id' => $id
                                              ,'commission_type' => \Config::get('constant.sysrep_comtype.seven_commission') //セブンイレブン手数料等
                                              ,'performance_id' => $ticketPrice->performance_id                              //公演id
                                              ,'payment_type'    => \Config::get('constant.sysrep_paytype.pickup')           //金額区分 2:発券手数料
                                              ,'apply_date'    => $ticketPrice->pickup_apply_date                            //摘要日
                                              ,'unit_price'      => round($ticketPrice->tax * $ticketPrice->pickup_amount)   //単価
                                              ,'number'          => 0                            //件数
                                              ,'sheets_number'   => $ticketPrice->seats_num      //枚数
                                              ,'amount'          => $ticketCommission           //金額,
                                              ]);
                //代理受領手数料 = 件数 * round(決済手数料 * 税率)
                $receiptCommission = $ticketPrice->reserve_num * round($ticketPrice->tax * $ticketPrice->payment_amount);
                $this->SysReportModel->insert([
                                               'id' => $id
                                              ,'commission_type' => \Config::get('constant.sysrep_comtype.seven_commission') //セブンイレブン手数料等
                                              ,'performance_id'  => $ticketPrice->performance_id                             //公演id
                                              ,'payment_type'    => \Config::get('constant.sysrep_paytype.receipt')          //金額区分 8:代理受領手数料
                                              ,'apply_date'    => $ticketPrice->payment_apply_date                           //摘要日
                                              ,'unit_price'      => round($ticketPrice->tax * $ticketPrice->payment_amount)  //単価
                                              ,'number'          => $ticketPrice->reserve_num    //件数
                                              ,'sheets_number'   => 0                            //枚数                        
                                              ,'amount'          => $receiptCommission            //金額,
                                              ]);               
              }
            }

        }catch(Exception $e){
            Log::info('getTicketSoldPrice error :'.$e->getMessage());
            throw new Exception ('getTicketSoldPrice error :'.$e->getMessage());
        }
        return $SoldInfo;
    }
    /**
     * getTicketSoldComissionCard
     * @param $id,$performanceId,$dateFrom,$dateTo,$payMethod
     * @return $ticketPriceList
     */
    public function getTicketSoldCommission($id,$performanceId,$dateFrom,$dateTo,$payMethod){    
        
        try{
             //【システム精算明細書】クレジットカード受領代金-発券手数料、サービス利用料
             //                      セブン代理受領代金--発券手数料、支払手数料、サービス利用料
            
             //カード/セブン支払の時の購入者からの支払内訳です。
             //発券手数料は購入金額中のセブン発券（Gettiis110円)、QR発券・電子チケット発券（55円）の合計です。        
             $subQuery = $this->makeQueryGetSalesPriceFromPerfomanceId($performanceId,$dateFrom,$dateTo,$payMethod);
             $subQuery = $subQuery->select(
                                           DB::raw('distinct GL_GENERAL_RESERVATION.order_id')
                                          ,'GL_SCHEDULE.performance_id'
                                          ,'GL_GENERAL_RESERVATION.commission_ticket as commission_ticket'   //発券手数料
                                          ,'GL_GENERAL_RESERVATION.commission_sv as commission_sv'           //サービス利用料
                                          ,'GL_GENERAL_RESERVATION.commission_payment as commission_payment' //支払手数料（コンビニ）
                                          );
             $SoldInfo = DB::table( DB::raw("({$subQuery->toSql()}) as sub") )
                         ->mergeBindings($subQuery->getQuery()) 
                         ->select(
                                  'performance_id'
                                 ,DB::raw('sum(commission_ticket) as commission_ticket')   //発券手数料
                                 ,DB::raw('sum(commission_sv) as commission_sv')           //サービス利用料
                                 ,DB::raw('sum(commission_payment) as commission_payment') //支払手数料（コンビニ）
                                 )
                         ->groupby('performance_id')
                         ->get();

            foreach( $SoldInfo as $ticketCommission)
            {
              $this->SysReportModel->insert([
                                             'id' => $id
                                            ,'commission_type' => ($payMethod == \Config::get('constant.pay_method.card')) ? \Config::get('constant.sysrep_comtype.card_receipt') : \Config::get('constant.sysrep_comtype.seven_receipt') //精算区分 1:クレジットカード受領代金 2:セブン代理
                                            ,'performance_id'  => $ticketCommission->performance_id              //公演id
                                            ,'payment_type'    => \Config::get('constant.sysrep_paytype.pickup') //金額区分 2:発券手数料
                                            ,'unit_price'      => 0                                    //単価
                                            ,'number'          => 0                                    //件数
                                            ,'sheets_number'   => 0                                    //枚数
                                            ,'amount'          => $ticketCommission->commission_ticket //金額,
                                            ]);
              $this->SysReportModel->insert([
                                             'id' => $id
                                            ,'commission_type' => ($payMethod == \Config::get('constant.pay_method.card')) ? \Config::get('constant.sysrep_comtype.card_receipt') : \Config::get('constant.sysrep_comtype.seven_receipt') //精算区分 1:クレジットカード受領代金 2:セブン代理
                                            ,'performance_id'  => $ticketCommission->performance_id               //公演id
                                            ,'payment_type'    => \Config::get('constant.sysrep_paytype.service') //金額区分 3:サービス利用料
                                            ,'unit_price'      => 0                                 //単価
                                            ,'number'          => 0                                 //件数
                                            ,'sheets_number'   => 0                                 //枚数
                                            ,'amount'          => $ticketCommission->commission_sv  //金額,
                                            ]);
              
              if($payMethod == \Config::get('constant.pay_method.store'))
              {
                $this->SysReportModel->insert([
                                               'id' => $id
                                              ,'commission_type' => \Config::get('constant.sysrep_comtype.seven_receipt') //2:セブン代理
                                              ,'performance_id'  => $ticketCommission->performance_id                     //公演id
                                              ,'payment_type'    => \Config::get('constant.sysrep_paytype.amount')        //金額区分 5:支払手数料
                                              ,'unit_price'      => 0                                      //単価
                                              ,'number'          => 0                                      //件数
                                              ,'sheets_number'   => 0                                      //枚数
                                              ,'amount'          => $ticketCommission->commission_payment  //金額,
                                              ]);
              }
            }  

        }catch(Exception $e){
            Log::info('getTicketSoldCommission error :'.$e->getMessage());
            throw new Exception ('getTicketSoldCommission error :'.$e->getMessage());
        }
        return;
    }
    /**
     * getTicketSoldCancel
     * @param $GLID,$id,$performanceId,$dateFrom,$dateTo,$payMethod
     * @return $ticketPriceList
     */
    public function getTicketSoldCancel($GLID, $id,$performanceId,$dateFrom,$dateTo,$payMethod){    
        try{
             //【システム精算明細書】クレジットカード受領代金-キャンセル代金
             //                      クレジットカード手数料等-キャンセル手数料
             //GL_CANSEL_ORDER キャンセル枚数
             $cancel_sheets_num = $this->ScheduleModel->leftJoin('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                      ->leftJoin('GL_CANCEL_ORDER',function($join) use ($dateFrom,$dateTo)
                                                        {                     
                                                          $join->on('GL_SEAT_SALE.order_id','=','GL_CANCEL_ORDER.order_id')
                                                               ->where('GL_CANCEL_ORDER.refund_kbn', CancelOrderModel::REFUND_CC)                                                               
                                                               ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.created_at, "%Y/%m/%d")'), '>=' , $dateFrom) //キャンセル処理の有った期間
                                                               ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.created_at, "%Y/%m/%d")'), '<=' , $dateTo);   //キャンセル処理の有った期間
                                                        })
                                                      ->whereIn('GL_SCHEDULE.performance_id', $performanceId)
                                                      ->select(
                                                               'GL_SCHEDULE.performance_id',
                                                               DB::raw('nvl(count(GL_CANCEL_ORDER.order_id),0) as cancel_sheets_num')
                                                              )
                                                      ->groupby('GL_SCHEDULE.performance_id')->get();
          
             foreach( $cancel_sheets_num as $CancelInfo)
             {
               //キャンセル枚数 insert
               $this->SysReportModel->insert([
                                              'id' => $id
                                             ,'commission_type'=> \Config::get('constant.sysrep_comtype.card_receipt') //精算区分 1:クレジットカード受領代金
                                             ,'performance_id' => $CancelInfo->performance_id                           //公演id
                                             ,'payment_type'   => \Config::get('constant.sysrep_paytype.cancel_fee')   //金額区分 4:キャンセル代金
                                             ,'unit_price'     => 0                               //単価
                                             ,'number'         => 0                               //件数
                                             ,'sheets_number'  => $CancelInfo->cancel_sheets_num  //枚数
                                             ,'amount'         => 0                               //金額,
                                             ]);  
             }   
                                                                                    
             //subquery GL_SEAT_SALE order_id
             $seatSale = DB::table('GL_SEAT_SALE')->whereNotNull('order_id')
                                                  ->select(
                                                           DB::raw('distinct order_id')
                                                          ,'schedule_id'
                                                          );
             
             //クレジットカード受領代金-キャンセル件数/キャンセル金額 クレジットカード手数料等-キャンセル手数料[円]
             $CardCancelInfo = $this->ScheduleModel->leftJoin(DB::raw('('. $seatSale->toSql() .') as CANCEL_NUM'), 'GL_SCHEDULE.schedule_id', '=', 'CANCEL_NUM.schedule_id')
                                                   ->mergeBindings($seatSale)
                                                   ->leftJoin('GL_CANCEL_ORDER','GL_CANCEL_ORDER.order_id','=','CANCEL_NUM.order_id')
                                                   ->select(
                                                            'GL_SCHEDULE.performance_id'
                                                           ,DB::raw('count(CANCEL_NUM.order_id) as cancel_num')
                                                           ,DB::raw('nvl(sum(GL_CANCEL_ORDER.refund_payment) ,0)as refund_payment')
                                                           ,DB::raw($this->makeQueryGetTaxRate('GL_CANCEL_ORDER.created_at') . 'as tax') //税率
                                                           )
                                                   ->selectRaw($this->makeQueryGetRateAmount('?' ,\Config::get('constant.client_commission.cancel'),'GL_CANCEL_ORDER.created_at',false) . ' as amount',[$GLID]) //手数料
                                                   ->selectRaw($this->makeQueryGetApplyPeriod('?' ,\Config::get('constant.client_commission.cancel'),'GL_CANCEL_ORDER.created_at')  . 'as apply_date ',[$GLID]) //手数料摘要日
                                                   ->whereIn('GL_SCHEDULE.performance_id', $performanceId)
                                                   ->where('GL_CANCEL_ORDER.refund_kbn', CancelOrderModel::REFUND_CC) 
                                                  ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.created_at, "%Y/%m/%d")'), '>=' , $dateFrom) //キャンセル処理の有った期間
                                                  ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.created_at, "%Y/%m/%d")'), '<=' , $dateTo)    //キャンセル処理の有った期間
                                                   ->groupby('GL_SCHEDULE.performance_id','GL_CANCEL_ORDER.created_at')
                                                   ->get();

             //キャンセル手数料
             foreach($CardCancelInfo as $CancelInfo)
             {
               //クレジットカード受領代金-キャンセル件数
               $this->SysReportModel->where('id', $id)
                                    ->where('performance_id', $CancelInfo->performance_id)
                                    ->where('commission_type', \Config::get('constant.sysrep_comtype.card_receipt'))
                                    ->where('payment_type', \Config::get('constant.sysrep_paytype.cancel_fee'))
                                    ->update([
                                              'number' => DB::raw('number+'.$CancelInfo->cancel_num)     //件数
                                             //,'amount' => DB::raw('amount+'.$CancelInfo->refund_payment) //金額,
                                             ]);  
               
               //クレジットカード手数料等-キャンセル手数料 = 件数 * round(キャンセル手数料[円] * 税率)
               $commissionAmount =  $CancelInfo->cancel_num * round($CancelInfo->amount * $CancelInfo->tax);
               $this->SysReportModel->updateOrInsert(
                                                     [
                                                      'id' => $id
                                                     ,'performance_id'  => $CancelInfo->performance_id                             //公演id
                                                     ,'commission_type' => \Config::get('constant.sysrep_comtype.card_commission') //精算区分 3:クレジットカード手数料
                                                     ,'payment_type'    => \Config::get('constant.sysrep_paytype.cancel_amount')   //金額区分 7:キャンセル手数料
                                                     ,'apply_date'    => $CancelInfo->apply_date                                 //有効期限
                                                     ],
                                                     [

                                                    'unit_price'     => round($CancelInfo->amount * $CancelInfo->tax) //単価
                                                    ,'number'        => DB::raw('number+'.$CancelInfo->cancel_num)    //件数
                                                    ,'sheets_number' => 0                                             //枚数
                                                    ,'amount'        => DB::raw('amount+'.$commissionAmount)          //金額,
                                                     ]
                                                    );
             }
             
             //クレジットカード手数料等-キャンセル手数料[%]
             $CardCancelInfo = $this->ScheduleModel->leftJoin('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                                   ->leftJoin('GL_CANCEL_ORDER','GL_CANCEL_ORDER.order_id','=','GL_SEAT_SALE.order_id')
                                                   ->select(
                                                            'GL_SCHEDULE.performance_id'
                                                           ,DB::raw('sum(GL_SEAT_SALE.sale_price) as sale_price')
                                                           ,DB::raw($this->makeQueryGetTaxRate('GL_CANCEL_ORDER.created_at') . 'as tax') //税率
                                                           )
                                                   ->selectRaw($this->makeQueryGetRateAmount('?' ,\Config::get('constant.client_commission.cancel'),'GL_CANCEL_ORDER.created_at',true) . ' as rate',[$GLID]) //手数料
                                                   ->selectRaw($this->makeQueryGetApplyPeriod('?' ,\Config::get('constant.client_commission.cancel'),'GL_CANCEL_ORDER.created_at')  . 'as apply_date ',[$GLID]) //手数料摘要日
                                                   ->whereIn('GL_SCHEDULE.performance_id', $performanceId)
                                                   ->whereNotNull('GL_SEAT_SALE.order_id')
                                                   ->where('GL_CANCEL_ORDER.refund_kbn', CancelOrderModel::REFUND_CC) 
                                                   ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.created_at, "%Y/%m/%d")'), '>=' , $dateFrom) //キャンセル処理の有った期間
                                                   ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.created_at, "%Y/%m/%d")'), '<=' , $dateTo)    //キャンセル処理の有った期間
                                                   ->groupby('GL_SCHEDULE.performance_id','GL_SEAT_SALE.order_id','GL_CANCEL_ORDER.created_at')->get();
             
              //キャンセル手数料
             foreach($CardCancelInfo as $CancelInfo)
             {
               
               //クレジットカード受領代金-キャンセル代金 LS#1541 「キャンセル代金」には、チケット代金だけ計上
               $this->SysReportModel->where('id', $id)
                                    ->where('performance_id', $CancelInfo->performance_id)
                                    ->where('commission_type', \Config::get('constant.sysrep_comtype.card_receipt'))
                                    ->where('payment_type', \Config::get('constant.sysrep_paytype.cancel_fee'))
                                    ->update([
                                             'amount' => DB::raw('amount+'.$CancelInfo->sale_price) //金額,
                                             ]);  
                 
                 
               //クレジットカード手数料等-キャンセル手数料 = チケット金額 * round(キャンセル手数料[%] * 税率)
               $commissionAmount =  round(round($CancelInfo->sale_price * $CancelInfo->rate) * $CancelInfo->tax);
               $this->SysReportModel->updateOrInsert(
                                                     [
                                                      'id' => $id
                                                     ,'performance_id'  => $CancelInfo->performance_id                             //公演id
                                                     ,'commission_type' => \Config::get('constant.sysrep_comtype.card_commission') //精算区分 3:クレジットカード手数料
                                                     ,'payment_type'    => \Config::get('constant.sysrep_paytype.cancel_amount')   //金額区分 7:キャンセル手数料
                                                     ,'apply_date'    => $CancelInfo->apply_date                                   //有効期限
                                                     ],
                                                     [
                                                      'unit_rate'     => $CancelInfo->rate * 100//料率
                                                     ,'amount'        => DB::raw('amount+'.$commissionAmount) //金額,
                                                     ]
                                                    );
             }            

        }catch(Exception $e){
            Log::info('getTicketSoldCancel error :'.$e->getMessage());
            throw new Exception ('getTicketSoldCancel error :'.$e->getMessage());
        }
        return $CardCancelInfo;
    }
    /**
     * getCreditCardPaymentCommission
     * @param $id,$performanceId,$dateFrom,$dateTo,$payMethod
     * @return $ticketPriceList
     */
    public function getCreditCardPaymentCommission($id,$performanceId,$dateFrom,$dateTo){    
        
        try{
             //【システム精算明細書】クレジットカード手数料等-決済手数料
             //subquery
             $ticketSale = \DB::table('GL_SCHEDULE')->Join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                               ->whereIn('GL_SCHEDULE.performance_id', $performanceId)
                                               ->where('GL_SEAT_SALE.payment_flg', 1) 
                                               ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '>=',$dateFrom) 
                                               ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '<=',$dateTo) 
                                               ->select(
                                                        'GL_SCHEDULE.performance_id'
                                                       ,'GL_SEAT_SALE.order_id'
                                                       ,'GL_SEAT_SALE.payment_date'
                                                       ,DB::raw('sum(GL_SEAT_SALE.sale_price) as sale_price')   //チケット代金
                                                       )
                                               ->groupby('GL_SCHEDULE.performance_id','GL_SEAT_SALE.order_id','GL_SEAT_SALE.payment_date');             
             
             //１件当たり*料率（四捨五入）、更に消費税をかけて四捨五入）
             $CardCommission = $this->GeneralReservationModel->Join(DB::raw('('. $ticketSale->toSql() .') as GL_SEAT_SALE'), 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_SEAT_SALE.order_id')
                                                             ->mergeBindings($ticketSale)
                                                             ->select(
                                                                      'GL_SEAT_SALE.performance_id'
                                                                       ,DB::raw('sum(round(round((GL_SEAT_SALE.sale_price + GL_GENERAL_RESERVATION.commission_sv + GL_GENERAL_RESERVATION.commission_ticket) 
                                                                              * ' . $this->makeQueryGetRateAmount('GL_GENERAL_RESERVATION.GLID',\Config::get('constant.client_commission.card_payment'),'GL_SEAT_SALE.payment_date',true) . //手数料(%)
                                                                             ' + ' . $this->makeQueryGetRateAmount('GL_GENERAL_RESERVATION.GLID',\Config::get('constant.client_commission.card_payment'),'GL_SEAT_SALE.payment_date',false) . //手数料(円)
                                                                       '         )  * ' . $this->makeQueryGetTaxRate('GL_SEAT_SALE.payment_date') . ')) as card_comission')           //税率
                                                                     )            
                                                             ->where('GL_GENERAL_RESERVATION.pay_method', \Config::get('constant.pay_method.card'))
                                                             ->groupby('GL_SEAT_SALE.performance_id')
                                                             ->get();
             foreach( $CardCommission as $Commission)
             {
               //クレジットカード手数料等-決済手数料
               $this->SysReportModel->insert([
                                              'id' => $id
                                             ,'commission_type' => \Config::get('constant.sysrep_comtype.card_commission') //精算区分 3:クレジットカード手数料等
                                             ,'performance_id' => $Commission->performance_id                              //公演id
                                             ,'payment_type'    => \Config::get('constant.sysrep_paytype.settlement')      //金額区分 6:決済手数料
                                             ,'unit_price'      => 0                               //単価
                                             ,'number'          => 0                               //件数
                                             ,'sheets_number'   => 0                               //枚数
                                             ,'amount'          => $Commission->card_comission     //金額,
                                             ]);  
             }      
             
        }catch(Exception $e){
            Log::info('getCreditCardPaymentCommission error :'.$e->getMessage());
            throw new Exception ('getCreditCardPaymentCommission error :'.$e->getMessage());
        }
        return;  
    }    
    /**
     * getRunningCommission
     * @param $id,$performanceId,$dateFrom,$dateTo
     * @return $ticketPriceList
     */
    public function getRunningCommission($id,$performanceId,$dateFrom,$dateTo){    
        
        try{
             //【システム精算明細書】ランニング-システム利用料（一般）

            $tt = new Carbon($dateTo);
            $dateToN = $tt->addDays(1)->format('Y/m/d');

             $cancelList = $this->CancelOrderModel::where('refund_kbn',2)
                                                    ->whereBetween('created_at', [$dateFrom, $dateToN])
                                                    ->get();
            $canceledOIDList = $cancelList->map(function ($item, $key) {
                                    return $item->order_id;
                                })
                                ->all();
            
             $ticketSale = \DB::table('GL_SCHEDULE')->Join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                               ->whereIn('GL_SCHEDULE.performance_id', $performanceId)
                                               ->where('GL_SEAT_SALE.payment_flg', 1) 
                                               ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '>=',$dateFrom) 
                                               ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '<=',$dateTo) 
                                               ->whereNotIn('order_id',$canceledOIDList)
                                               ->select(
                                                        'GL_SCHEDULE.performance_id'
                                                       ,'GL_SEAT_SALE.order_id'
                                                       ,'GL_SEAT_SALE.payment_date'
                                                       ,'GL_SEAT_SALE.sale_price'
                                                       );
                                                       
             // １件当たり*料率（四捨五入）、更に消費税をかけて四捨五入）
             $runningCommission = $this->GeneralReservationModel->Join(DB::raw('('. $ticketSale->toSql() .') as GL_SEAT_SALE'), 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_SEAT_SALE.order_id')
                                                                ->mergeBindings($ticketSale)
                                                                ->select(
                                                                         'GL_SEAT_SALE.performance_id'
                                                                         ,DB::raw('count(*) as seatCount')
                                                                         ,DB::raw('sum(round((round(GL_SEAT_SALE.sale_price *'. $this->makeQueryGetRateAmount('GL_GENERAL_RESERVATION.GLID',\Config::get('constant.client_commission.system'),'GL_SEAT_SALE.payment_date',true) .')+' .
                                                                                              $this->makeQueryGetRateAmount('GL_GENERAL_RESERVATION.GLID',\Config::get('constant.client_commission.system'),'GL_SEAT_SALE.payment_date',false) .  ')*'. 
                                                                                              $this->makeQueryGetTaxRate('GL_SEAT_SALE.payment_date') .')'.') as running_comission'
                                                                                 )
                                                                        )            
                                                                ->whereIn('GL_GENERAL_RESERVATION.pay_method', [\Config::get('constant.pay_method.card'),\Config::get('constant.pay_method.store')])
                                                                ->groupby('GL_SEAT_SALE.performance_id')
                                                                ->get();
            
             foreach( $runningCommission as $Commission)
             {
               //ランニング-システム利用料（一般）
               $this->SysReportModel->insert([
                                              'id' => $id
                                             ,'commission_type' =>  \Config::get('constant.sysrep_comtype.running') //精算区分 5:ランニング
                                             ,'performance_id'  => $Commission->performance_id                      //公演id
                                             ,'payment_type'    => \Config::get('constant.sysrep_paytype.system')   //金額区分 9:システム利用料（一般）
                                             ,'unit_price'      => 0                               //単価
                                             ,'number'          => 0                               //件数
                                             ,'sheets_number'   => $Commission->seatCount          //枚数
                                             ,'amount'          => $Commission->running_comission  //金額,
                                             ]);  
             }      
             
        }catch(Exception $e){
            Log::info('getRunningCommission error :'.$e->getMessage());
            throw new Exception ('getRunningCommission error :'.$e->getMessage());
        }
        return;  
    }    

    /**
     * getCommissionRateAmount
     * @param $id
     * @return $summaryinfo
     */
    public function getCommissionRateAmount($GLID,$commissionType,$applyDateFrom,$applyDateTo){    
      //料率手数料取得
      try {
        $commissionInfo = $this->CommissionClientModel->select('rate','amount')
                               ->where('GLID', $GLID) 
                               ->where('commission_type', '=',$commissionType) 
                               //->where('apply_date', '>=',$applyDateFrom) 
                               ->where('apply_date', '<=',$applyDateTo) 
                               ->where('delete_flg', 0)
                               ->get()->toArray();
        
        if(count($commissionInfo) == 0)
        {
          //手数料。料率の登録なしの場合
          $commissionInfo[0] = array(
                                      "rate"   => 0,
                                      "amount" => 0,
                                     );   
        }
      }catch(Exception $e){
        Log::info('getCommissionRateAmount error :'.$e->getMessage());
        throw new Exception ('getCommissionRateAmount error :'.$e->getMessage());
      }
      return $commissionInfo;
    }    
    
    /**
     * getSysReportSummaryInfo
     * @param $id
     * @return $summaryinfo
     */
    public function getSysReportSummaryInfo($id){    
        try {
               $summaryinfo = $this->SysReportModel
                            ->select('commission_type'
                                    ,'payment_type'
                                    ,'apply_date'
                                    ,'unit_price' 
                                    ,'unit_rate' 
                                    ,DB::raw('sum(number) as number')
                                    ,DB::raw('sum(sheets_number) as sheets_number')
                                    ,DB::raw('sum(amount) as amount')
                                    )
                            ->where('id', $id)
                           ->groupby ('commission_type','payment_type','apply_date','unit_price','unit_rate')
                           ->get();
         
        }catch(Exception $e){
            Log::info('getSysReportSummaryInfo error :'.$e->getMessage());
            throw new Exception ('getSysReportSummaryInfo error :'.$e->getMessage());
        }
        return $summaryinfo;          
    }    

    /**
     * getSysReportInfo
     * @param $id
     * @return $summaryinfo
     */
    public function getSysReportInfo($id){    
        try {
               $eventSummary = $this->SysReportModel
                               ->select('performance_id'
                                       ,'commission_type'
                                       ,'payment_type'
                                       ,'apply_date'
                                       ,DB::raw('sum(unit_price) as unit_price') 
                                       ,DB::raw('sum(unit_rate) as unit_rate') 
                                       ,DB::raw('sum(number) as number')
                                       ,DB::raw('sum(sheets_number) as sheets_number')
                                       ,DB::raw('sum(amount) as amount')
                                       )
                               ->where('id', $id)
                               ->groupby ('performance_id','commission_type','payment_type','apply_date')
                               ->get();           
            
        }catch(Exception $e){
            Log::info('getSysReportInfo error :'.$e->getMessage());
            throw new Exception ('getSysReportInfo error :'.$e->getMessage());
        }
        return $eventSummary;          
    }    

    /**
     * deleteSysReportInfo
     * @param $id
     */
    public function deleteSysReportInfo($id){    
        try {
               $this->SysReportModel->where('id', $id)
                                    ->delete();           
        }catch(Exception $e){
            Log::info('deleteSysReportInfo error :'.$e->getMessage());
            throw new Exception ('deleteSysReportInfo error :'.$e->getMessage());
        }
        return;          
    }
    
    /**
     * getSalePointOutputTarget
     * @return $target
     */
    public function getSalePointOutputTarget(){    
        try {
               $target = $this->GeneralReservationModel
                              ->select('GL_GENERAL_RESERVATION.reserve_no','GL_USER.user_code')
                              ->join('GL_USER','GL_GENERAL_RESERVATION.GLID','=','GL_USER.GLID')
                              ->leftJoin('GL_SEAT_SALE', 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_SEAT_SALE.order_id')
                              ->whereNotNull('GL_GENERAL_RESERVATION.order_id')
                              ->where('GL_GENERAL_RESERVATION.pay_method', \Config::get('constant.pay_method.store'))
                              ->where('GL_GENERAL_RESERVATION.cancel_flg', \Config::get('constant.order_cancel_flg.off'))
                              ->where('GL_SEAT_SALE.payment_flg', 1) 
                              ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '=' , date("Y/m/d",strtotime("-1 day"))) 
                              ->groupby ('GL_GENERAL_RESERVATION.reserve_no','GL_USER.user_code')
                              ->get();
        }catch(Exception $e){
            Log::info('getSalePointOutputTarget error :'.$e->getMessage());
            throw new Exception ('getSalePointOutputTarget error :'.$e->getMessage());
        }
        return $target;          
    }    
    
    /**
     * getCancelSevenUnpaidPointOutputTarget
     * @return $target
     */
    public function getCancelSevenUnpaidPointOutputTarget(){
        //seven支払い ポイント使用キャンセル
        try {
               $target = $this->GeneralReservationModel
                              ->select('GL_GENERAL_RESERVATION.reserve_no','GL_USER.user_code')
                              ->join('GL_CANCEL_ORDER','GL_GENERAL_RESERVATION.order_id','=','GL_CANCEL_ORDER.order_id')
                              ->join('GL_USER','GL_GENERAL_RESERVATION.GLID','=','GL_USER.GLID')
                              ->leftJoin('GL_SEAT_SALE', 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_SEAT_SALE.order_id')
                              ->whereNotNull('GL_GENERAL_RESERVATION.order_id')
                              ->where('GL_GENERAL_RESERVATION.cancel_flg', \Config::get('constant.order_cancel_flg.on'))
                              ->where('GL_SEAT_SALE.payment_flg', 0) 
                              ->where('GL_GENERAL_RESERVATION.pay_method', \Config::get('constant.pay_method.store'))
                              ->where('GL_GENERAL_RESERVATION.use_point', '<>',0) 
                              ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.refund_due_date, "%Y/%m/%d")'), '=' , date("Y/m/d",strtotime("-1 day"))) 
                              ->groupby ('GL_GENERAL_RESERVATION.reserve_no','GL_USER.user_code')
                              ->get();
        }catch(Exception $e){
            Log::info('getCancelPointOutputTarget error :'.$e->getMessage());
            throw new Exception ('getCancelPointOutputTarget error :'.$e->getMessage());
        }
        return $target;          
    }   

    /**
     * getCancelPointOutputTarget
     * @return $target
     */
    public function getCancelPaidPointOutputTarget(){
        try {
               $target = $this->GeneralReservationModel
                              ->select('GL_GENERAL_RESERVATION.reserve_no','GL_USER.user_code')
                              ->join('GL_CANCEL_ORDER','GL_GENERAL_RESERVATION.order_id','=','GL_CANCEL_ORDER.order_id')
                              ->join('GL_USER','GL_GENERAL_RESERVATION.GLID','=','GL_USER.GLID')
                              ->leftJoin('GL_SEAT_SALE', 'GL_GENERAL_RESERVATION.order_id', '=', 'GL_SEAT_SALE.order_id')
                              ->whereNotNull('GL_GENERAL_RESERVATION.order_id')
                              ->where('GL_GENERAL_RESERVATION.cancel_flg', \Config::get('constant.order_cancel_flg.on'))
                              ->where('GL_SEAT_SALE.payment_flg', 1) 
                              ->where(DB::raw('DATE_FORMAT(GL_CANCEL_ORDER.refund_due_date, "%Y/%m/%d")'), '=' , date("Y/m/d",strtotime("-1 day"))) 
                              ->groupby ('GL_GENERAL_RESERVATION.reserve_no','GL_USER.user_code')
                              ->get();
        }catch(Exception $e){
            Log::info('getCancelPointOutputTarget error :'.$e->getMessage());
            throw new Exception ('getCancelPointOutputTarget error :'.$e->getMessage());
        }
        return $target;          
    }    

    /**
     * makeQueryGetSalesPriceFromPerfomanceId
     * @param $performanceId,$dateFrom,$dateTo,$payMethod
     * @return $SoldInfo
     */
     private function makeQueryGetSalesPriceFromPerfomanceId($performanceId,$dateFrom,$dateTo,$payMethod){
     
       $SoldInfo =  $this->ScheduleModel->Join('GL_SEAT_SALE','GL_SCHEDULE.schedule_id','=','GL_SEAT_SALE.schedule_id')
                                        ->Join('GL_GENERAL_RESERVATION','GL_SEAT_SALE.order_id','=','GL_GENERAL_RESERVATION.order_id')
                                        ->whereIn('GL_SCHEDULE.performance_id', $performanceId)
                                        ->whereNotNull('GL_GENERAL_RESERVATION.order_id')
                                        ->where('GL_GENERAL_RESERVATION.pay_method', $payMethod)
                                        ->where('GL_SEAT_SALE.payment_flg', 1)
                                        ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '>=' , $dateFrom)
                                        ->where(DB::raw('DATE_FORMAT(GL_SEAT_SALE.payment_date, "%Y/%m/%d")'), '<=' , $dateTo);       
       
       
       return $SoldInfo;      
     }    
    /**
     * makeQueryGetRateAmount
     * @param $GLID,$commissionType,$applyDate
     * @return $query
     */
     private function makeQueryGetRateAmount($GLID,$commissionType,$applyDate,$getRate){
       //料率取得
       $query = ($getRate) ? 'nvl((select rate/100' : 'nvl((select amount';   
       $query .= $this->makeQueryGetCommissionClient($GLID,$commissionType,$applyDate) . ',0)';
       return $query;      
     }    

      /**
     * makeQueryGetTaxRate
     * @param $commissionType
     * @return $query
     */
     private function makeQueryGetTaxRate($applyDate){
       //税率取得     
       $query =  '(select 1 + rate/100';
       $query .= $this->makeQueryGetCommissionClient(1,\Config::get('constant.client_commission.system'),$applyDate); 
       return $query;      
     }    
    /**
     * makeQueryGetApplyPeriod
     * @param $commissionType
     * @return $query
     */
     private function makeQueryGetApplyPeriod($GLID,$commissionType,$applyDate){
       //摘要日取得     
       $query =  '(select apply_date';
       $query .= $this->makeQueryGetCommissionClient($GLID,$commissionType,$applyDate);
       return $query;      
     }    
    /**
     * makeQueryGetCommissionClient
     * @param $commissionType
     * @return $query
     */
     private function makeQueryGetCommissionClient($GLID,$commissionType,$applyDate){
       //税率率取得     
       $query =  ' from   GL_COMMISSION_CLIENT
                   where  GLID =  '. $GLID .
                 '   and  commission_type = ' .$commissionType .
                 '   and  apply_date <= ' . $applyDate .
                 '   and  delete_flg = 0 ' .
                 ' order by apply_date desc
                   limit 1
                  )'; 
       return $query;      
     }    

     public function getOrderByReserveno($reserve_no) {
        $this->GeneralReservationModel = GeneralReservationModel::where('reserve_no', $reserve_no)->first();
        if($this->GeneralReservationModel)
            return true;
        return false;
     }

     public function getOrderByOrderID($orderID) {
        try {
            $this->GeneralReservationModel = GeneralReservationModel::findOrFail($orderID);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
     }

     public function getAPISitebyOrder() {
        if(!$this->GeneralReservationModel)
            return null;
        $gsSite = $this->GeneralReservationModel->gsSite;
        return $gsSite->url_api;
     }

     public function getReserveNo() {
        if(!$this->GeneralReservationModel)
            return null;
        return $this->GeneralReservationModel->reserve_no;
     }

    /**
     * insert portalMstOutput date
     * @param array $data
     * @return result 
     */ 
    public function portalMstOutputInsert($data)
    {     
        Log::debug('SellManageRepositories.portalMstOutputInsert');
        try{
            $result = $this->PortalMstOutputModel->insert(
                [
                    'sight_id'=>$data['sight_id'],
                    'data_id'=>$data['data_id'],
                    'data_kbn'=>$data['data_kbn'],
                    'output_date'=>$data['output_date'],
                    'corp_target'=>$data['corp_target'],
                    'file_name'=>$data['file_name'],
                ]
            );  

            return $result;
        }catch(Exception $e){
            Log::info('SellManageRepositories :'.date("y/m/d h:m:s").$e->getMessage());
        }
    }
    /**
     * get bank inf
     * @param string $glid
     * @return collections 
     */ 
    public function getBankInf($glid)
    {
        try {
            return UserManageModel::findOrFail($glid);
         }catch (Exception $e) {
            Log::error('getBankInf :'.$e->getMessage());
            throw new Exception ('getStageData error :'.$e->getMessage());
         }
    }

    /**
     * フリーアンケート 2021/04/09 LS-Itabashi
     * get question data
     * @param $performanceId
     * @return $questionList
     */
    public function getQuestion($performanceId) {
       
        try {
            $questionList = $this->QuestionModel->with('questionLangJa')
            ->where('GL_QUESTION.performance_id', $performanceId)
            ->where('GL_QUESTION.use_flg', 1)
            ->orderBy('GL_QUESTION.disp_order')
            ->get();

        } catch(Exception $e) {
            Log::error('getQuestion error :'.$e->getMessage());
            throw new Exception ('getQuestion error :'.$e->getMessage());
        }
        return $questionList->toArray();
    }

    /**
     * update SeatSaleModel visit_status && visit_date
     * @param array $input
     * @param string $seat_sale_id
     * @return collections 
     */ 
    public function updateVisitStatus($input, $seat_sale_id)
    {
        try {
            SeatSaleModel::where('seat_sale_id', $seat_sale_id)->update($input);
            
            return collect(SeatSaleModel::findOrFail($seat_sale_id)->toArray());
        }catch (Exception $e) {
            Log::error('updateVisitStatus :'.$e->getMessage());
            throw new Exception ('updateVisitStatus error :'.$e->getMessage());
        }
    }
				/**
     * STS 2021/09/08 Task 48 No.2
     * get Order GLID
     * @param orderId
     * @return result
     */ 
    public function getOrderGLID($orderId){   
        try{
            $result = $this->GeneralReservationModel->select('GLID')
                                                    ->where('order_id', $orderId)
                                                    ->get();
            return $result->toArray();
        }catch(Exception $e){
            Log::info('function getOrderGLID| MiddlewareRepositories | error code :  | error messeger : '.$e->getMessage());
            App::abort(404);
        }
    }
}