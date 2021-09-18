<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralReservationModel;
use App\Models\SeatSaleModel;
use App\Models\MobapassOutputModel;
use Illuminate\Support\Facades\Hash;
use Exception;
use Log;
use App;

class MobapassOutputRepositories
{
    /** @var GeneralReservationModel */
    protected $GeneralReservationModel;
    /** @var SeatSaleModel */
    protected $SeatSaleModel;
    /** @var MobapassOutputModel */
    protected $MobapassOutputModel;
    /**
     * constructor.
     * @param GeneralReservationModel $GeneralReservationModel
     * @param MobapassOutputModel $MobapassOutputModel;
     */
    public function __construct(GeneralReservationModel $GeneralReservationModel, SeatSaleModel $SeatSaleModel, MobapassOutputModel $MobapassOutputModel)
    {
        $this->GeneralReservationModel = $GeneralReservationModel;
        $this->SeatSaleModel           = $SeatSaleModel;
        $this->MobapassOutputModel     = $MobapassOutputModel;
    }

    /**
     * get getMobapassOutputTarget
     * @param none
     * @return result 
     * mobapass連携対象取得
     */ 
    public function getMobapassOutputTarget()
    {   
        try{
            $block =  (\App::getLocale() == "ja" )? '"ブロック"':'"區"'; 
            $col   =  (\App::getLocale() == "ja" )? '"列"':'"排"'; 
            $seat  =  (\App::getLocale() == "ja" )? '"番"':'"號"'; 
            $result =  $this->GeneralReservationModel->select( 'GL_GENERAL_RESERVATION.order_id' 
                                                              ,'GL_USER.user_code' 
                                                              ,'GL_PERFORMANCE.performance_code'
                                                              ,DB::raw('DATE_FORMAT(GL_SCHEDULE.performance_date,"%Y/%m/%d") as performance_date')
                                                              ,DB::raw('CASE dayofweek(GL_SCHEDULE.performance_date)
                                                                         WHEN 1 THEN "（日）"
                                                                         WHEN 2 THEN "（月）"
                                                                         WHEN 3 THEN "（火）"
                                                                         WHEN 4 THEN "（水）"
                                                                         WHEN 5 THEN "（木）"
                                                                         WHEN 6 THEN "（金）"
                                                                         WHEN 7 THEN "（土）"
                                                                        END as day_week')                                                              ,'GL_STAGENAME.stage_num'
                                                              ,"GL_GENERAL_RESERVATION.reserve_no"
                                                              ,'GL_SEAT_SALE.reserve_seq'
                                                              ,'GL_PERFORMANCE.performance_name'
                                                              ,'GL_PERFORMANCE.performance_name_sub'
                                                              ,'GL_PERFORMANCE.sch_kbn'
                                                              ,'GL_PERFORMANCE.hall_disp_name'
                                                              ,DB::raw('DATE_FORMAT(GL_SCHEDULE.performance_date,"%w")as date_name')
                                                              ,'GL_STAGENAME.stage_name'
                                                              //,'GL_SCHEDULE.open_date'
                                                              //,DB::raw('"00:00" as open_date')
                                                              ,DB::raw('DATE_FORMAT(GL_SCHEDULE.start_time,"%H:%i") as start_time')
                                                              ,DB::raw('DATE_FORMAT(concat(GL_SCHEDULE.performance_date," 23:59:59"),"%Y/%m/%d %H:%i:%s") as expiration_date')
                                                              ,'GL_SEAT_CLASS.seat_class_id'
                                                              ,'GL_SEAT_CLASS.seat_class_kbn'
                                                              ,'GL_SEAT_CLASS.seat_class_name'
                                                              ,'GL_TICKET_CLASS.ticket_class_name'
                                                              ,DB::raw('TRUNCATE(GL_SEAT_SALE.sale_price,0) as sale_price')
                                                              ,'GL_HALL_SEAT.gate'
                                                              ,'GL_FLOOR.floor_name'
                                                              ,'GL_HALL_SEAT.seat_cols'
                                                              ,'GL_HALL_SEAT.seat_number'
                                                              ,'GL_PERFORMANCE.information_nm'
                                                              ,'GL_PERFORMANCE.information_tel'
                                                              ,'GL_GENERAL_RESERVATION.member_id'
                                                              ,'GL_MBPS_MBR.app_id'                 
                                                              ,DB::raw('DATE_FORMAT(GL_PERFORMANCE.performance_st_dt,"%Y/%m/%d") as performance_st_dt')
                                                              //,DB::raw('DATE_FORMAT(GL_GENERAL_RESERVATION.updated_at,"%Y/%m/%d %H:%i:%s") as updated_at')
                                                              ,'GL_GENERAL_RESERVATION.updated_at'
                                                              ,'GL_GENERAL_RESERVATION.update_account_cd'
                                                              ,'GL_SCHEDULE.disp_performance_date'
                                                              ,'GL_GENERAL_RESERVATION.receipt_no'                    
                                                              ,'GL_GENERAL_RESERVATION.cancel_flg'  
                                                              ,DB::raw('DATE_FORMAT(GL_GENERAL_RESERVATION.pickup_st_date,"%Y/%m/%d") as pickup_st_date')             
                                                              ,'GL_SEAT_CLASS.seat_class_id'
                                                              ,'GL_SEAT_SALE.seat_sale_id'
                                                              ,'GL_PERFORMANCE.performance_id'
                                                              ,'GL_PERFORMANCE.seatmap_profile_cd'                    
                                                              ,'GL_SEAT_SALE.schedule_id'
                                                             )
                                                      ->selectRaw('concat(GL_BLOCK.block_name, ?) as block_name', [$block])
                                                    //   ->selectRaw('concat(GL_HALL_SEAT.seat_cols, ?) as seat_cols', [$col])
                                                    //   ->selectRaw('concat(GL_HALL_SEAT.seat_number, ?) as seat_number', [$seat])
                                                      ->join('GL_SEAT_SALE','GL_GENERAL_RESERVATION.order_id','=','GL_SEAT_SALE.order_id')                    
                                                      ->join('GL_SCHEDULE','GL_SEAT_SALE.schedule_id','=','GL_SCHEDULE.schedule_id')
                                                      ->join('GL_STAGENAME','GL_SCHEDULE.stcd','=','GL_STAGENAME.stcd')        
                                                      ->join('GL_PERFORMANCE','GL_SCHEDULE.performance_id','=','GL_PERFORMANCE.performance_id')
                                                      ->join('GL_USER','GL_PERFORMANCE.GLID','=','GL_USER.GLID')                    
                                                      ->join('GL_HALL','GL_PERFORMANCE.hall_code','=','GL_HALL.hall_code')                    
                                                      ->join('GL_SEAT_CLASS','GL_SEAT_SALE.seat_class_id','=','GL_SEAT_CLASS.seat_class_id')
                                                      ->join('GL_TICKET_CLASS','GL_SEAT_SALE.ticket_class_id','=','GL_TICKET_CLASS.ticket_class_id')
                                                      ->join('GL_MBPS_MBR','GL_GENERAL_RESERVATION.member_id','=','GL_MBPS_MBR.member_id')                   
                                                      ->leftjoin('GL_SEAT','GL_SEAT_SALE.alloc_seat_id','=','GL_SEAT.alloc_seat_id')
                                                      ->leftjoin('GL_HALL_SEAT','GL_SEAT.seat_id','=','GL_HALL_SEAT.seat_id')
                                                      ->leftjoin('GL_FLOOR','GL_HALL_SEAT.floor_id','=','GL_FLOOR.floor_id')
                                                      ->leftjoin('GL_BLOCK','GL_HALL_SEAT.block_id','=','GL_BLOCK.block_id')
                                                      ->where('GL_GENERAL_RESERVATION.pickup_method', \Config::get('constant.pickup_method.eticket'))         //mobapass
                                                      //->where('GL_GENERAL_RESERVATION.cancel_flg', \Config::get('constant.mobapass_cancel_flg.off'))        //未キャンセル
                                                      ->where('GL_GENERAL_RESERVATION.mobapass_trans_flg', \Config::get('constant.mobapass_trans_flg.off'))   //未連携
                                                      ->where('GL_PERFORMANCE.performance_end_dt','>=', date("y/m/d H:i:s"))                                  //未終了
                                                      ->where('GL_MBPS_MBR.del_flg', '0')                                                                     //未削除
                                                      ->get();
            return $result; 
        }catch(Exception $e){
            Log::info('getMobapassOutputTarget :'.$e->getMessage());
            throw new Exception ('getMobapassOutputTarget :'.$e->getMessage());
        }
    }
    /**
     * get getMobapassOutputTarget
     * @param none
     * @return result 
     * mobapass取消対象取得
     */ 
    public function getMobapassCancelTarget()
    {    
        try{
            $result =  $this->GeneralReservationModel->select('GL_GENERAL_RESERVATION.order_id'
                                                             ,'GL_USER.user_code' 
                                                             ,'GL_PERFORMANCE.performance_code'
                                                             ,'GL_SCHEDULE.performance_date'
                                                             ,'GL_SCHEDULE.stcd'
                                                             ,'GL_GENERAL_RESERVATION.reserve_no'
                                                             ,'GL_SEAT_SALE.reserve_seq'
                                                             ,'GL_GENERAL_RESERVATION.cancel_flg'
                                                             ,'GL_SEAT_SALE.seat_sale_id'        
                                                             )
                                                      ->join('GL_SEAT_SALE','GL_GENERAL_RESERVATION.order_id','=','GL_SEAT_SALE.order_id')                    
                                                      ->join('GL_SCHEDULE','GL_SEAT_SALE.schedule_id','=','GL_SCHEDULE.schedule_id')
                                                      ->join('GL_PERFORMANCE','GL_SCHEDULE.performance_id','=','GL_PERFORMANCE.performance_id')                                                     
                                                      ->join('GL_USER','GL_PERFORMANCE.GLID','=','GL_USER.GLID')   
                                                      ->where('GL_GENERAL_RESERVATION.pickup_method', \Config::get('constant.pickup_method.eticket'))       //mobapass
                                                      ->where('GL_GENERAL_RESERVATION.cancel_flg',  \Config::get('constant.order_cancel_flg.on'))          //orderキャンセル済
                                                      ->where('GL_GENERAL_RESERVATION.mobapass_cancel_flg',  \Config::get('constant.mobapass_cancel_flg.off')) //mobapass未キャンセル
                                                      ->get();
            return $result; 
        }catch(Exception $e){
            Log::info('getMobapassCancelTarget :'.$e->getMessage());
            throw new Exception ('getMobapassCancelTarget :'.$e->getMessage());
        }
    }
    /**
     * get getMobapassTransferTarget
     * @param none
     * @return result 
     * mobapass譲渡対象取得
     */ 
    public function getMobapassTransferTarget()
    {    
        try{
            $result =  $this->GeneralReservationModel->distinct()->select('GL_MBPS_MBR.app_id'
                                                                         ,'GL_GENERAL_RESERVATION.updated_at'
                                                                         ,'GL_GENERAL_RESERVATION.update_account_cd'
                                                                         ,'GL_USER.user_code'
                                                                         ,'GL_GENERAL_RESERVATION.member_id'
                                                                         ,'GL_SEAT_SALE.seat_sale_id'                
                                                                         )
                                                      ->join('GL_SEAT_SALE','GL_GENERAL_RESERVATION.order_id','=','GL_SEAT_SALE.order_id')    
                                                      ->join('GL_MBPS_MBR','GL_GENERAL_RESERVATION.member_id','=','GL_MBPS_MBR.member_id') 
                                                      ->join('GL_USER','GL_GENERAL_RESERVATION.GLID','=','GL_USER.GLID')     
                                                      ->where('GL_GENERAL_RESERVATION.pickup_method', \Config::get('constant.pickup_method.eticket')) //mobapass
                                                      ->where('GL_GENERAL_RESERVATION.cancel_flg',  \Config::get('constant.mobapass_cancel_flg.off'))   //未キャンセル
                                                    //   ->where('GL_GENERAL_RESERVATION.reserve_expire', '>=' ,  date("y/m/d H:i:s")) //有効                    
                                                      ->where('GL_SEAT_SALE.visit_flg', '0') //未入場
                                                      ->where('GL_MBPS_MBR.del_flg', '0') //有効
                                                      ->get();
            return $result; 
        }catch(Exception $e){
            Log::info('getMobapassTransferTarget :'.$e->getMessage());
            throw new Exception ('getMobapassTransferTarget :'.$e->getMessage());
        }
    }
    /**
     * mbpsOutputInsert
     * @param $output
     * @return result 
     * GL_MOBAPASS_OUTPUT insert  
    */ 
    public function mbpsOutputInsert($output)
    {    
        try{
             $result = $this->MobapassOutputModel->insert(
                                                          [
                                                            'data_kbn' => $output['data_kbn'],
                                                            'data_id'  => $output['data_id'],
                                                            'file_name'=> $output['file_name'] ,                                                                
                                                          ]
                                                         );
             return $result; 
        }catch(Exception $e){
            Log::info('mbpsOutputInsert :'.$e->getMessage());
            throw new Exception ('mbpsOutputInsert :'.$e->getMessage());
        }
    }
    /**
     * generalReservationMbpsUpdate
     * @param order_id
     * @return result 
     * GL_GENERAL_RESERVATION update - mobapass_trans_flg
    */ 
    public function generalReservationMbpsUpdate($order_id,$flg)
    {    
        try{
             $result = $this->GeneralReservationModel->where('order_id', $order_id)
                                                     ->update(
                                                              [
                                                               'mobapass_trans_flg'=>$flg ,
                                                               'updated_at'=> date("y/m/d H:i:s"),
                                                              ]
                                                             );
             return $result; 
        }catch(Exception $e){
            Log::info('generalReservationMbpsUpdate :'.$e->getMessage());
            throw new Exception ('generalReservationMbpsUpdate :'.$e->getMessage());
        }
    }
    /**
     * generalReservationMbpsCancelUpdate
     * @param order_id
     * @return result 
     * GL_GENERAL_RESERVATION update - mobapass_cancel_flg
    */ 
    public function generalReservationMbpsCancelUpdate($order_id)
    {    
        try{
             $result = $this->GeneralReservationModel->where('order_id', $order_id)
                                                     ->update(
                                                              [
                                                               'mobapass_cancel_flg'=> \Config::get('constant.mobapass_cancel_flg.on') , //1:済
                                                               'updated_at'=> date("y/m/d H:i:s"),
                                                              ]
                                                             );
             return $result; 
        }catch(Exception $e){
            Log::info('generalReservationMbpsCancelUpdate :'.$e->getMessage());
            throw new Exception ('generalReservationMbpsCancelUpdate :'.$e->getMessage());
        }
    }
    /**
     * seatSaleIssueFlgUpdate
     * @param $order_id,$reserve_seq
     * @return result 
     * GL_SEAT_SALE update - issue_flg
    */ 
    public function seatSaleIssueFlgUpdate($order_id,$reserve_seq)
    {    
        try{
             $result = $this->SeatSaleModel->where('order_id', $order_id)
                                           ->where('reserve_seq', $reserve_seq)
                                           ->update(
                                                    [
                                                     'issue_flg'=> '1'
                                                    ,'issue_date'=> date("y/m/d H:i:s")
                                                    ,'updated_at'=> date("y/m/d H:i:s")
                                                    ]
                                                   );
             return $result; 
        }catch(Exception $e){
            Log::info('seatSaleIssueFlgUpdate :'.$e->getMessage());
            throw new Exception ('seatSaleIssueFlgUpdate :'.$e->getMessage());
        }
    }
    /**
     * get getMobapassOutputTargetByPerformanceId
     * @param none
     * @return result 
     * mobapass再連携対象取得
     */ 
    public function getMobapassOutputTargetByPerformanceId($performance_id)
    {    
        try{
            $result =  $this->GeneralReservationModel->select('GL_GENERAL_RESERVATION.order_id')
                                                     ->join('GL_SEAT_SALE','GL_GENERAL_RESERVATION.order_id','=','GL_SEAT_SALE.order_id')    
                                                     ->join('GL_SCHEDULE','GL_SEAT_SALE.schedule_id','=','GL_SCHEDULE.schedule_id')    
                                                     ->where('GL_GENERAL_RESERVATION.cancel_flg', \Config::get('constant.mobapass_cancel_flg.off')) //未キャンセル
                                                     ->where('GL_SCHEDULE.performance_id', $performance_id)
                                                     ->get();
            
            return (!empty($result))?$result:null; 
        }catch(Exception $e){
            Log::info('getMobapassOutputTargetByPerformanceId :'.$e->getMessage());
            throw new Exception ('getMobapassOutputTargetByPerformanceId :'.$e->getMessage());
        }
    }    
}
