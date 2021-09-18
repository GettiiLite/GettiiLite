<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralReservationModel;
use App\Models\SeatSaleModel;
use App\Models\MobapassInputModel;
use Illuminate\Support\Facades\Hash;
use Exception;
use Log;
use App;

class MobapassInputRepositories
{
    /** @var GeneralReservationModel */
    protected $GeneralReservationModel;
    /** @var SeatSaleModel */
    protected $SeatSaleModel;
    /**
     * constructor.
     * @param GeneralReservationModel $GeneralReservationModel
     */
    public function __construct(GeneralReservationModel $GeneralReservationModel, SeatSaleModel $SeatSaleModel, MobapassInputModel $MobapassInputModel)
    {
      $this->GeneralReservationModel = $GeneralReservationModel;
      $this->SeatSaleModel           = $SeatSaleModel;
      $this->MobapassInputModel      = $MobapassInputModel;
    }

    /**
     * get getMobapassOutputTarget
     * @param $rsvNumber
     * @return result 
     * order_id取得
     */ 
    public function getOrderIdByReserveNumber($rsvNumber)
    {    
        try{
            $result =  $this->GeneralReservationModel->select( 'GL_GENERAL_RESERVATION.order_id')
                                                     ->where('GL_GENERAL_RESERVATION.reserve_no', $rsvNumber)
                                                     ->first();
            return $result->order_id; 
        }catch(Exception $e){
            Log::info('getOrderIdByReserveNumber :'.$e->getMessage());
            throw new Exception ('getOrderIdByReserveNumber :'.$e->getMessage());
        }
    }
    /**
     * searchVisitFlg
     * @param $visitInfo
     * @return visit flg 
     * 入場フラグ検索
     */ 
    public function searchVisitFlg($order_id,$reserve_seq)
    {    
        try{
            $result =  $this->SeatSaleModel->select('visit_flg')
                                           ->where('order_id', $order_id)
                                           ->where('reserve_seq', $reserve_seq)
                                           ->first();
            return $result->visit_flg; 
        }catch(Exception $e){
            Log::info('searchVisitFlg:'.$e->getMessage());
            throw new Exception ('searchVisitFlg:'.$e->getMessage());
        }
    }    
    /**
     * visitFlgUpdate
     * @param $visitInfo
     * @return result 
     * 入場フラグ更新
     */ 
    public function visitFlgUpdate($visitInfo)
    {    
        try{
            $result =  $this->SeatSaleModel->where('order_id', $visitInfo['order_id'])
                                           ->where('reserve_seq', $visitInfo['reserve_seq'])
                                           ->update(
                                                    [
                                                     //'issue_flg'=> '1',  //ph3 LS#1563 発券フラグは入場時=>購入時     
                                                     'issue_date'=> date("y/m/d H:i:s"),
                                                     'visit_flg'=> $visitInfo['visit_flg'] , 
                                                     'visit_date'=> $visitInfo['visit_date'] , 
                                                     'updated_at'=> date("y/m/d H:i:s"),
                                                    ]
                                                   );
            return $result; 
        }catch(Exception $e){
            Log::info('visitFlgUpdate :'.$e->getMessage());
            throw new Exception ('visitFlgUpdate :'.$e->getMessage());
        }
    }
    /**
     * mbpsInputInsert
     * @param $output
     * @return result 
     * GL_MOBAPASS_INPUT insert  
    */ 
    public function mbpsInputInsert($input)
    {    
        try{
             $result = $this->MobapassInputModel->insert(
                                                          [
                                                            'data_kbn' => $input['data_kbn'],
                                                            'data_id'  => $input['data_id'],
                                                          ]
                                                         );
             return $result; 
        }catch(Exception $e){
            Log::info('mbpsInputInsert :'.$e->getMessage());
            throw new Exception ('mbpsInputInsert :'.$e->getMessage());
        }
    }    
}
