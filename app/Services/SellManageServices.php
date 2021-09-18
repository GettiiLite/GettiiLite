<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\SellManageRepositories;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\EvenManageRepositories;
use App\Services\MailServices;
use App\Services\GetApiServices;
use App\Services\PostApiServices;
use App\Repositories\MemberRepositories;
use App\Repositories\UserExRepositories;
use Illuminate\Support\Facades\DB;
use App\LSPayment;
use Log;
use Exception;
use Carbon\Carbon;
use GLHelpers;
use App; //STS 2021/09/09 Task 48 No.2

use function GuzzleHttp\json_decode;

class SellManageServices
{

    /** @var SellManageRepositories */
    protected $SellManageRepositories;
    /** @var  EvenManageRepositories */
    protected $EvenManageRepositories;
    /** @var GetApiServices */
    protected $GetApiServices;
    /** @var MemberRepositories */
    protected $MemberRepositories;
    /** @var PostApiServices */
    protected $PostApiServices;

    const PAGE_SIZE     = 10;
    const NORMAL_ORDER  = 1;
    const RES_ORDER     = 2;
    const NONRES_ORDER  = 3;

    /**
     * UserController constructor.
     * @param SellManageRepositories
     */
    public function __construct(
        SellManageRepositories $SellManageRepositories, 
        EvenManageRepositories $EvenManageRepositories, 
        MailServices $SendMail,
        GetApiServices $GetApiServices,
        MemberRepositories $MemberRepositories,
        PostApiServices $PostApiServices,
        UserExRepositories $UserExRepositories
    ){
        $this->SellManageRepositories = $SellManageRepositories;
        $this->EvenManageRepositories = $EvenManageRepositories;
        $this->SendMail               = $SendMail;
        $this->GetApiServices         = $GetApiServices;
        $this->MemberRepositories     = $MemberRepositories;
        $this->PostApiServices        = $PostApiServices;
        $this->UserExRepositories     = $UserExRepositories;
    }
    /**
     * 查詢條件轉換
     * @param string $json
     * @return array $result
     */
    protected function getOrderFilterInf($json = null){
        $result =   array(
            'performanceId' => null,
            'schedulesId' => null,
            'filter' => false,
            'keyword' => '',
            "seatType" => '',
            "ticketType" => '',
            "dateRangeStar" => null,
            "dateRangeEnd"  => null,
            "pay_method" => \Config::get('constant.pay_method'),
            "not_pickup_method" => false,
            "pickup_method" => \Config::get('constant.pickup_method'),
            "noTissue" => false,
            "issue" => [0, 1, 2],
            "receipt" => [0, 1, 2],
            "seatOrder" => true,
            "seatFree" => true,
            "seatReserve" => true,
            'sale_type' => [0, 1],
            'seat_class' => [1, 2],
            'order_status' => [-99, -21, -3, -2, -1, 0, 1, 2, 3],
        );
       
        if($json){
            $filter_json = json_decode($json, true);
            $result['filter'] = true;
            $result['keyword'] = trim($filter_json['inf']['keyword']);
            $result['seatType'] = isset($filter_json['inf']['seatType'])? $filter_json['inf']['seatType'] : null;
            $result['ticketType'] = isset($filter_json['inf']['ticketType'])? $filter_json['inf']['ticketType'] : null;
            $result['dateRangeStar'] = null;
            $result['dateRangeEnd'] = null;
            $result['not_pickup_method'] = $filter_json['inf']['noTPickup'];
            $result['noTissue'] = $filter_json['inf']['noTissue'];
            $result['seatOrder'] = $filter_json['inf']['seatOrder'];
            $result['seatFree'] = $filter_json['inf']['seatFree'];
            $result['seatReserve'] = $filter_json['inf']['seatReserve'];

            if($result['keyword']){
                switch($result['keyword']){
                    case '非會員':
                    case '非会員':
                        $result['keyword'] = '[N_M]';
                        break;
                }
            }
           
            if(isset($filter_json['inf']['performanceId'])){
                $result['performanceId'] = ($filter_json['inf']['performanceId'] > 0)? $filter_json['inf']['performanceId'] : null;
            }
            
            if(isset($filter_json['inf']['schedulesId'])){
                $result['schedulesId'] = ($filter_json['inf']['schedulesId'] > 0)? $filter_json['inf']['schedulesId'] : null;
            }

            //座席種類
            //自由席
            if(!$filter_json['inf']['seatOrder']){
                unset($result['seat_class'][0]);
            }
            //指定席
            if(!$filter_json['inf']['seatFree']){
                unset($result['seat_class'][1]);
            }
            //保留席 - sale tyle 判斷 
            if(!$filter_json['inf']['seatReserve']){
                $result['sale_type'][1];
            }
            //付款方式
            if(!$filter_json['inf']['notPaymentMethod']){
                unset($result['pay_method']['not']);
            }
            //if(!$filter_json['inf']['payCash']){
                unset($result['pay_method']['cash']);
            //}
            if(!$filter_json['inf']['payCredit']){
                unset($result['pay_method']['card']);
            }
            if(!$filter_json['inf']['payIbon'] || \App::getLocale() != "zh-tw"){
                unset($result['pay_method']['ibon']);
            }
            if(!$filter_json['inf']['paySevenEleven'] || \App::getLocale() != "ja"){
                unset($result['pay_method']['store']);
            }
            if(!$filter_json['inf']['payFree']){
                unset($result['pay_method']['free']);
            }
            //是否付款
            if(!$filter_json['inf']['noReceipt']){
                unset($result['receipt'][0]);
            }
            if(!$filter_json['inf']['receipt']){
                unset($result['receipt'][1]);
            }
            if(!$filter_json['inf']['notReceipt']){
                unset($result['receipt'][2]);
            }
            //取票方式
            unset($result['pickup_method']['onsite']);
            // mobapass 冷凍中
            unset($result['pickup_method']['eticket']);

            if(!$filter_json['inf']['qrpass'] || \App::getLocale() != "zh-tw"){
                unset($result['pickup_method']['qrpass_sms']);
                unset($result['pickup_method']['qrpass_email']);
            }
            if(!$filter_json['inf']['ibon'] || \App::getLocale() != "zh-tw"){
                unset($result['pickup_method']['ibon']);
            }
            if(!$filter_json['inf']['sevenEleven'] || \App::getLocale() != "ja"){
                unset($result['pickup_method']['store']);
            }
            if(!$filter_json['inf']['resuq']){
                unset($result['pickup_method']['resuq']);
            }
            if(!$filter_json['inf']['noTicketing']){
                unset($result['pickup_method']['no_ticketing']);
            }
            //出票
            if($filter_json['inf']['issue'] == $filter_json['inf']['noIssue']){
                unset($result['issue']['2']);
            }
            if(!$filter_json['inf']['issue']){
                unset($result['issue']['1']);
            }
            if(!$filter_json['inf']['noIssue']){
                unset($result['issue']['0']);
            }
            //訂購日期區間
            if($filter_json['inf']['dateRangeStar']){
               $date_range_star = str_replace("/","-",$filter_json['inf']['dateRangeStar']).' 00:00:00';
               $result['dateRangeStar'] = date($date_range_star);
            }
            if($filter_json['inf']['dateRangeEnd']){
                $date_range_end = str_replace("/","-",$filter_json['inf']['dateRangeEnd']).' 23:59:59';
                $result['dateRangeEnd'] = date($date_range_end);
            }
            //訂單狀態
            if(!$filter_json['inf']['orderStatus']['normal']){
                unset($result['order_status']['5']);
                unset($result['order_status']['6']);
                unset($result['order_status']['7']);
                unset($result['order_status']['8']);
            }
            if(!$filter_json['inf']['orderStatus']['cancel']){
                unset($result['order_status']['0']);
                unset($result['order_status']['1']);
                unset($result['order_status']['2']);
                unset($result['order_status']['4']);
            }
            if(!$filter_json['inf']['orderStatus']['timeoutCancel']){
                unset($result['order_status']['3']);
            }
            if(!$filter_json['inf']['orderStatus']['systemCancel']){
                unset($result['order_status']['3']);
            }
        }
        
        return $result;
    }
    /**
     * 
     * 取得問卷 csv 格式
     * 
     */
    private function getCsvQuestion($answers){
        $result = false;

        if($answers){
            foreach($answers as $answer){
                $question_lang = $answer->questionLang;
                $locale = \Config::get('app.locale');

                $question = $question_lang->firstWhere('lang_code', $locale);
                
                $question_title = "";

                if($question){
                    if($question['question']->use_flg){
                        $question_title = $question->question_text;
                        $question_answer = $answer->answer_text;

                        $result[] = array(
                            "question" => $question_title, 
                            "answer" => $question_answer
                        );
                    }
                }
            }
        }
        
        return $result;
    }
    private function getOrderStatus($order_data)
    {
        //訂單取消類型 0:無 1：一般取消 2：逾時取消 3：系統取消
        $result  = array(
            'order_status_cancel'   => false,
            'order_cancel_type'     => 0,
            'order_cancel_reason'   => '',
        );
        if($order_data['cancel_flg'] == \Config::get('constant.order_cancel_flg.off')){
            // if(($order_data['pay_method'] == \Config::get('constant.pay_method.ibon') || $order_data['pay_method'] == \Config::get('constant.pay_method.store'))
            //     && $order_data['seat_sale'][0]['payment_flg'] == 0 ){
            //     $now_date = now();
            //     if(strtotime($now_date) > strtotime("5 hours", strtotime($order_data['reserve_expire'] ))){
            //         $result['order_status_cancel'] = true;
            //         $result['order_cancel_type']   = 2;
            //         $result['order_cancel_reason'] = trans('sellManage.S_CancelNotice07');
            //     }
            // }
        }else{
            if($order_data['pay_method'] == 0) {
                $result['order_status_cancel'] = true;
                $result['order_cancel_type']   = 3;
                $result['order_cancel_reason'] = trans('sellManage.S_CancelNotice08');
            }
            else {
                if($order_data['seat_sale'][0]['seat_status'] == -2){
                    $result['order_status_cancel'] = true;
                    $result['order_cancel_type']   = 2;
                    $result['order_cancel_reason'] = trans('sellManage.S_CancelNotice07');
                }
                else {
                    $result['order_status_cancel'] = true;
                    $result['order_cancel_type']   = 1;
                    $result['order_cancel_reason'] = trans('sellManage.S_CancelNotice09');    
                }
            }
        }

        return $result;
    }

    private function getStartDatePerformanceDispStatus($performance)
    {
      $salesTerm = $this->EvenManageRepositories->getSalesTermDate($performance["performance_id"]);
      $normalstartDate = '';
      foreach ($salesTerm as $salsInfo) {
        if ($salsInfo->sales_kbn ===  \Config::get('constant.ticket_sales_kbn.early')) {
          //先行販売
          $earlystartDate = date('Y/m/d',  strtotime($salsInfo->reserve_st_date)) . ' ' . $salsInfo->reserve_st_time;
        } else if ($salsInfo->sales_kbn ===  \Config::get('constant.ticket_sales_kbn.normal')) {
          //一般販売
          $normalstartDate = date('Y/m/d',  strtotime($salsInfo->reserve_st_date)) . ' ' . $salsInfo->reserve_st_time;
        }
      }
      //販売期間 from
      $starDate = empty($earlystartDate) ? $normalstartDate : $earlystartDate;
      return $starDate;
    }

    private function getPerformanceDispStatus($value, $starDate)
    {
      switch($value["status"]) {
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
          $disp_status = ($value["trans_flg"] == '0') ? \Config::get('constant.performance_disp_status.browse') : \Config::get('constant.performance_disp_status.public');
        break;
       
        //販売可
        case \Config::get('constant.performance_status.sale') : 
          $now = strtotime("now");
          if ($now >= strtotime($starDate) && $value["trans_flg"] > 0) {
          //処理日 <= 販売開始日 且つ 連携フラグON    
            $disp_status = \Config::get('constant.performance_disp_status.saling');
            // if ($now >= strtotime($value["performance_st_dt"])) {
            if(Carbon::now()->gt(Carbon::parse($value["performance_st_dt"]." 00:00:00"))) {
              //処理日 >= 公演開始日
              // $status = '期間中';     
              $disp_status = \Config::get('constant.performance_disp_status.ongoing');
            }
            // if ($now >= strtotime($value["performance_end_dt"])) {
            if(Carbon::now()->gt(Carbon::parse($value["performance_end_dt"]." 23:59:59"))) {
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

      if($value['trans_flg'] > 0 && $value['sale_type'] == 0 && $disp_status > \Config::get('constant.performance_disp_status.sale')){
        $disp_status = \Config::get('constant.performance_disp_status.sale');
      }

      return $disp_status;
    }


    private function getPerformanceDispStatusStr($dispStatus)
    {
      switch($dispStatus) {
        case config('constant.performance_disp_status.going'):
          $dispStatusStr = trans('common.S_StatusCode_0');
        break;

        case config('constant.performance_disp_status.complete'):
          $dispStatusStr = trans('common.S_StatusCode_1');
        break;

        case config('constant.performance_disp_status.browse'):
          $dispStatusStr = trans('common.S_StatusCode_2');
        break;

        case config('constant.performance_disp_status.public'):
          $dispStatusStr = trans('common.S_StatusCode_2_1');
        break;

        case config('constant.performance_disp_status.sale'):
          $dispStatusStr = trans('common.S_StatusCode_3');
        break;

        case config('constant.performance_disp_status.saling'):
          $dispStatusStr = trans('common.S_StatusCode_4');
        break;
        
        case config('constant.performance_disp_status.ongoing'):
          $dispStatusStr = trans('common.S_StatusCode_5');
        break;
        
        case config('constant.performance_disp_status.close'):
          $dispStatusStr = trans('common.S_StatusCode_6');
        break;
        
        case config('constant.performance_disp_status.cancel'):
          $dispStatusStr = trans('common.S_StatusCode_7');
        break;
        default:
          $dispStatusStr = '--';
        break;
      }
      return $dispStatusStr;
    }

    /**
     * 
     * status : N - nothing 
     * status : I - insert data 
     * status : U - upload data 
     * 
     */
    public function uploadUnpublishedData($json_data){
        $result = array(
            "result" => true,
            "message" => '成功',
        );
    
        try {
            $draft_id = $json_data->draftId;
            $draft = $this->SellManageRepositories->getDraftData($draft_id);
            $mapData_change = $json_data->mapData;
            $type_seat = $json_data->typeSeat;
            $date_value = $json_data->dateValue;
            $rule_id = $json_data->ruleId;
        
            if($draft){
                $draft_info = json_decode($draft['draft_info']);
                $draft_info->mapData[0]->data[0]->mapData = $mapData_change;

                //自由席數量單場設定檢查
                foreach($type_seat as $type_data){
                    if($type_data->status == 'U' && $type_data->type == 3){
                        if($draft_info->ticketSeeting->ticketSetting->settingType === 'freeSeat') {
                            $update_type = $draft_info->ticketSeeting->ticketSetting->data;
                        }
                        else {
                            $update_type = $draft_info->ticketSeeting->ticketSetting->data[$type_data->index];
                        }
                        
                        if(!isset($update_type->respectiveData)){
                            $update_type->respectiveData = array();
                        }
                        $ticket_key = -1;
                        $is_same = false;
                        //檢查資料是否相同
                        if($draft_info->ticketSeeting->ticketSetting->settingType === 'freeSeat') {
                            if($update_type->seatQty == $type_data->typeTotal){
                                $is_same = true;
                            }    
                        }
                        else {
                            if($update_type->seatTotal == $type_data->typeTotal){
                                $is_same = true;
                            }    
                        }
                        foreach($update_type->respectiveData as $key => $respective){
                            if($respective->dateValue == $date_value && $respective->ruleId == $rule_id){
                                $ticket_key = $key;
                                break;
                            }
                        }
                        if($ticket_key !== -1){
                            if($is_same){
                                array_splice($update_type->respectiveData, $ticket_key, 1);
                            }else{
                                $update_type->respectiveData[$ticket_key]->total = $type_data->typeTotal;
                            }
                        }else{
                            if(!$is_same){
                                $update_type->respectiveData[] = (object) [
                                    'dateValue' => $date_value,
                                    'ruleId' => $rule_id,
                                    'total' => $type_data->typeTotal,
                                ];
                            }
                        }
                        if($draft_info->ticketSeeting->ticketSetting->settingType === 'freeSeat') {
                            $draft_info->ticketSeeting->ticketSetting->data = $update_type;;
                        }
                        else {
                            $draft_info->ticketSeeting->ticketSetting->data[$type_data->index] = $update_type;
                        }

                    }
                }
            
                $update_data = array(
                    'draft_id' => $draft_id,
                    'draft_info' => json_encode($draft_info),
                );
                $update_result = $this->SellManageRepositories->updateDraftData($update_data);
                
                if(!$update_result){
                    throw new Exception(trans('儲存錯誤'));
                }
            }else{
                throw new Exception(trans('資料出現錯誤'));
            }
        }catch (Exception $e) {
            Log::debug('editorImangeUpload'.$e->getMessage());
      
            $result = array(
              "result" => false,
              "message" => $e->getMessage(),
            );
        
        }

        return $result;
    }
    /**
     * 
     * status : N - nothing 
     * status : I - insert data 
     * status : U - upload data 
     * 
     */
    public function uploadSellSeatData(array $request){
        $json = json_decode($request['json'], true);
        $performanceId = $json[0]['performanceId'];
        $scheduleId = $json[0]['scheduleId'];
        $reserveSeatData = $json[0]['reserveSeatData'];
        $mapData = $json[0]['mapData'];
        $type_seat = $json[0]['typeSeat'];
        $account_cd = session('account_cd');
        $update_result = array(
            'scheduleId' => $scheduleId,
            'book_error' => false,
            'seat_book' => [],
        );
        
        //保留類型新增 
        //27 - 10 - 2020 mark 日本版未使用新增保留席功能
        // foreach($reserveSeatData as $key => $value){
        //     if($value['status'] == 'I'){
        //         $reserve_code = $this->SellManageRepositories->insertReserveSeat($account_cd, $performanceId, $value);
        //         $temporary_info = $this->SellManageRepositories->getPerformanceJsonData($performanceId);
        //         $eventData = json_decode($temporary_info[0]['temporary_info'], true);

        //         $eventData["ticketSeeting"]["option"][] = array(
        //                                                     "id" => 0,
        //                                                     "value" => $value['reserve_name'],
        //                                                 );

        //         $eventData["ticketSeeting"]["specTicketSetting"]["data"][] = array(
        //                                                                         "tickerId" => $key, 
        //                                                                         "ticketCode" => $reserve_code,
        //                                                                         "ticketColor" => $value['text_color'],
        //                                                                         "ticketName" => $value['reserve_name'],
        //                                                                         "ticketStatus" => 'N',
        //                                                                         "ticketText" => $value['text'],
        //                                                                         "ticketTotal" => $value['total']
        //                                                                     );
        //         $tempinfo = array(
        //             'performance_id' => $performanceId,
        //             'account_cd' => $account_cd,
        //             'temporary_info' =>  json_encode($eventData),
        //         );
        //         $this->SellManageRepositories->performanceJsonUpdate($tempinfo);
        //         $reserveSeatData[$key]['reserve_code'] = $reserve_code;
        //     }
        // }
        
        foreach($mapData as $floor){
            foreach($floor['blockData'] as $block){
                foreach($block['seatData'] as $value){
                    if($value['status'] == 'U'){ 
                        $check_status = $this->SellManageRepositories->getSeatNowStatus($value['alloc_seat_id'], $scheduleId);
                        if($check_status){
                            $seatData = $this->SellManageRepositories->getSeatData($value['alloc_seat_id'], $scheduleId);
                           
                            if($value['type'] == '0'){
                                if($seatData['stage_seat_id']){
                                    $this->SellManageRepositories->updateNullStageSeatData($scheduleId, $account_cd, $value);
                                }else if(!$seatData['stage_seat_id']){
                                    $this->SellManageRepositories->insertNullStageSeatData($scheduleId, $account_cd, $value);
                                }else{

                                }
                            }else if($value['type'] > 0 && $value['type'] < 3){
                                $seat_update_data  = array(
                                    'alloc_seat_id' => $value['alloc_seat_id'],
                                    'schedule_id' => $scheduleId,
                                    'reserve_code' => null,
                                    'seat_class_id' => null,
                                    'update_account_cd' => $account_cd,
                                );

                                if($value['type'] == '1'){
                                    if($value['typeId'] ==  $seatData['reserve_code'] && $seatData['stage_seat_id']){
                                        $this->SellManageRepositories->deleteStageSeatData($seatData['stage_seat_id']);
                                        continue;
                                    }

                                    $seat_update_data['reserve_code'] = $value['typeId'];
                                }

                                if($value['type'] == '2'){
                                    if($value['typeId'] ==  $seatData['seat_class_id'] && $seatData['stage_seat_id']){
                                        $this->SellManageRepositories->deleteStageSeatData($seatData['stage_seat_id']);
                                        continue;
                                    }
                                    $seat_update_data['seat_class_id'] = $value['typeId'];
                                }

                                if($seatData['stage_seat_id']){
                                    $this->SellManageRepositories->updateClassStageSeatData($seat_update_data);
                                }else{
                                    $this->SellManageRepositories->insertClassStageSeatData($seat_update_data);
                                }
                            }else{
                                $update_result['book_error'] = true;
                                $update_result['seat_book'][] = array(
                                    'floor' => $floor['floorTittle'],
                                    'block' => $block['blockTittle'],
                                    'gate' => $block['gate'],
                                    'number' => $value['number'],
                                    'rowname' => $value['rowname'],
                                );
                            }
                        }else{
                            $update_result['book_error'] = true;
                            $update_result['seat_book'][] = array(
                                'floor' => $floor['floorTittle'],
                                'block' => $block['blockTittle'],
                                'gate' => $block['gate'],
                                'number' => $value['number'],
                                'rowname' => $value['rowname'],
                            );
                        }
                    }
                }
            }
        }
        
        foreach($type_seat as $seat_data){
            if($seat_data['seat_class_kbn'] == 2 &&
               $seat_data['status'] == 'U' &&
               $seat_data['stock_id'] != ''
            ){  
                $nonreserved_stock_result = $this->SellManageRepositories->getNonreservedStockId($seat_data['stock_id']);

                if($nonreserved_stock_result){
                    $update_stock_data = array(
                        'stock_limit' => $seat_data['typeTotal'],
                        'update_account_cd' => $account_cd
                    );

                    $this->SellManageRepositories->updateSaleData($update_stock_data);
                }

            }
           
        }
        
        return $update_result;
    }
    /**
     * 訂單取消
     * 
     * @param array $request
     * @return $result
     */
    public function orderCancel(array $request)
    {   
        $json           = json_decode($request['json'], true);
        $order_id       = $json[0]['inf'][0]['orderId'];
        $bankInf        = json_encode($json[0]['inf'][0]['bankinf'][0]);
        $refund_kbn     = ($json[0]['inf'][0]['refund_kbn'] == \Config::get('constant.pay_method.card'))?2:1;
        $refund_payment = $json[0]['inf'][0]['refundPayment'];
        $use_point      = $json[0]['inf'][0]['use_point'];
        $account_cd     = (session('account_cd'))?session('account_cd'):$json[0]['accountCd'];
								
		//STS 2021/09/08 Task 48 No.2 start.
        if(session('admin_flg')){
            $orderInf = $this->SellManageRepositories->getOrderGLID($order_id);
            if($orderInf[0]['GLID'] != session('GLID')){
                Log::info(session('order_id').' is not sales info flg');
                App::abort(404);
                return;
            }
        }
        //STS 2021/09/08 Task 48 No.2 end.
        
        //call lspayment cencel api
        $data = array(
            'order_id'          => $order_id,
            'refund_kbn'        => $refund_kbn,
            'refund_inf'        => $bankInf,
            'refund_payment'    => intval($refund_payment) - intval($use_point),
            'update_account_cd' => $account_cd,
        );
       
        $apiRet = LSPayment\Api::cencelOrder($data);
       
        //if error return error msg
        //if successs : cancel order and seat
        if($apiRet->statusCode == 9){
            $result  = array(
                'status' => true,
                'msn'    => trans('sellManage.S_SucceedOrderCancel'),
            );    
        }else{
            $result['status'] = false;
            $result['msn'] = $apiRet->msn;
            log:info($data);
            Log::error($apiRet->msn);
        }
       
        return $result;
    }
    /**
     * 訂單金額修改
     * 
     * @param array $request
     * @return $result
     */
    public function reviseAmount(array $request){
        $json               = json_decode($request['json'], true);
        $order_id           = $json[0]['inf'][0]['order_id'];
        $amount_total       = $json[0]['inf'][0]['amount_total'];
        $amount_memo        = $json[0]['inf'][0]['amount_memo'];
        $revise_info_json   = $json[0]['inf'][0]['revise_info'];
        $revise_info        = json_decode($revise_info_json, true);
        $account_cd         = session('account_cd');
        
        $result  = array(
            'status' => false,
            'msn'    => trans('sellManage.S_FailureReviseAmount'),
        );

        $data = array(
            'order_id'      => $order_id,
            'amount_total'  => $amount_total,
            'amount_memo'   => $amount_memo,
            'revise_info'   => $revise_info_json,
            'account_cd'    => $account_cd,
        );
        
        $revise_amount_result = $this->SellManageRepositories->reviseAmount($data);

        foreach($revise_info as $value){
           $this->SellManageRepositories->updateSalePrice($value);
        }

        if($revise_amount_result){
            // call frontend amount change api
            $patch_data = array(
                'price'              => $amount_total,
            );
            if($this->PostApiServices->amountChange($order_id, $patch_data)){
                Log::info('Update amountChange to FE has succeed.');
            }
            else {
                Log::error('Update amountChange to FE has failed.');
            }
            //
            $result['status'] =  true;
            $result['msn'] =  trans('sellManage.S_SucceedReviseAmount');
        }
    
        return $result;
    }
    /**
     * 取得 gettis 會員資料
     * 
     * @parm string $keyword
     * @return $json
     */
    public function getMembers($keyword){
        $perPage     = 8;
        $userInf     = [];
        $searchTotal = 0;
        $apiSite = $this->MemberRepositories->getAPISite(session('GLID'));
        $data = array(
            'url'   =>  $apiSite.'/members?page=1&perPage='.$perPage.'&keyWord='.$keyword,
        );
       
        if(!is_null($keyword)){
            $apiData = $this->GetApiServices->get($data);

            $memberData     = $apiData['data']['memberData'];
            $userData       = $memberData[0]['data'][0]['userInf'];
            $searchTotal    = $memberData[0]['status']['total'];
        
            foreach($userData as $value){
                $userInf[]  =   array(
                    'id'            =>  $value['id'],
                    'user_id'       =>  $value['user_id'],
                    'name'          =>  $value['name'],
                    'status'        =>  $value['status'],
                    'tel'           =>  $value['tel'],
                    'email'         =>  $value['email'],
                    'moba_id'         =>  isset($value['moba_id'])?$value['moba_id']:null,
                );
            }
        }

        $status = array(
            'searchTotal'   =>  $searchTotal,
            'keyword'       =>  $keyword,
        );
 
        $resultData  =   array(
            'userInf'   =>   $userInf,
        );
 
        $result = array(
            'status'   =>   $status,
            'data'     =>   $resultData,
        );

        $json = json_encode($result);
        
        return $json;
    }
    /**
     * get sell manage seat map date information
     * 
     * @parm string performance_id
     * @return array
     */
    public function getSeatMapData($scheduleId){
        $reserveSeat = [];
        $typeSeat = [];
        $seatClassData = $this->SellManageRepositories->getScheduleDataMapPage($scheduleId);
        $performanceData = $this->SellManageRepositories->getPerformanceData($seatClassData->performance_id);
        $reserveData = $this->SellManageRepositories->getReservationData($seatClassData->performance_id);
        $reserveSeatData = $this->SellManageRepositories->getScheduleSelect_RESSeatData($seatClassData->performance_id, $scheduleId);//固定席位 //STS 2021/08/09 Task 25
        $seatType = $this->SellManageRepositories->getSeatTypeData($seatClassData->performance_id);//一般席位
        $seatTypeData = $this->SellManageRepositories->getPerformanceSeatTypeData($seatClassData->performance_id, $scheduleId);
        $seatSelect = 0;
        $seatReserve = 0;
        $seatTotal = 0;
        $seat_receive = 0;
        $reserveSeatNum = 1;
        $reserveSeatIdChange = [];
        $upload_result = array(
            'upload'     => false,   
            'book_error' => false,
            'message'   => '',
            'seat_book' => []
        );

        if(session()->exists('upload_result')){
            $upload_result_session = session('upload_result');
            $upload_result['upload'] = true;
           
            if($upload_result_session['book_error']){
                $upload_result['book_error'] = true;
                $upload_result['seat_book'] = $upload_result_session['seat_book'];
            }else{
                $upload_result['book_error'] = false;
            }
        }

        //保留席計算
        foreach($reserveData as $key => $value){
            $reserveCode = $value->reserve_code;
            $reserveNum = 0;
            $is_order = 0;
           
            foreach($reserveSeatData as $key => $data){
                if($data->stage_seat_id){
                    if($data->stage_reserve_code == $reserveCode){
                        $reserveNum++;
                        if(!is_null($data->order_id)){
                            $is_order++;
                        }
                    }
                }else{
                    if($data->reserve_code == $reserveCode){
                        $reserveNum++;
                        if(!is_null($data->order_id)){
                            $is_order++;
                        }
                    }
                }
            }
          
            $reserveSeat[] = array(
                "index" => $key,
                'reserve_code' => $value->reserve_code,
                'reserve_name' => $value->reserve_name,
                'color' => $value->reserve_color,
                'text' => $value->reserve_symbol,
                'text_color' => $value->reserve_word_color,
                'order_total' => $is_order,
                "type" => -1,
                'total' => $reserveNum,
                'status' => 'N',
            );

            $seatTotal += $reserveNum;
            $seat_receive   += $is_order;
            $reserveSeatIdChange[$value->reserve_code] = $reserveSeatNum;

            $seatReserve += $reserveNum;
            $reserveSeatNum++;
        }
       
        $seatMap = [];
        $seatInf = [];
        $sellStatusData = [];
        $seatMapData = $this->SellManageRepositories->getSeatViewsData($seatClassData->performance_id, $scheduleId);
        $seatSaleData = $this->SellManageRepositories->getSeatSellStatuc($seatClassData->performance_id, $scheduleId);
        $now_date = date('Y-m-d');
    
        //判斷是否逾期
        foreach($seatSaleData as $value){
            $expire = false;
            
            if($value->cancel_flg == \Config::get('constant.order_cancel_flg.on')){
                $expire = true;
            }

            // if(strtotime($now_date) > strtotime($value->reserve_expire)){
            //     switch($value->pay_method){
            //         case \Config::get('constant.pay_method.store'):
            //         case \Config::get('constant.pay_method.ibon'):
            //             if($value->payment_flg == 0){
            //                 $expire = true;
            //             }
            //             break;
            //     }
            // }
            // if($value->seat_status == 1){
            //     if(is_null($value->order_id)){
            //         $add_time = strtotime("-15 minutes", strtotime($now_date)); 
            //         $expire_date = date('Y-m-d H:i:s', $add_time); 
            //         if(strtotime($value->temp_reserve_date) > strtotime($expire_date)){
            //             $expire = false;
            //         }
            //     }else{
            //         if(strtotime($value->reserve_expire) > strtotime($now_date)){
            //             $expire = false;
            //         }
            //     }
            // }

            if(!$expire){
                $sellStatusData[$value['alloc_seat_id']] = $value;
            }
           
        }
        
        $reserveInf     = [];
        $typeInf        = [];
        $freeseat_sum   = 0;
       
        foreach($seatMapData as $value){

            $lx_coordinate = 0;
            $ly_coordinate = 0;

            switch ($value['seat_direction']) {
                case 2: //↓
                case 1: //↑
                default:
                    $lx_coordinate = $value['x_coordinate'];
                    $ly_coordinate = $value['y_coordinate'];
                    break;
                case 3: //←
                case 4: //→
                    $lx_coordinate = $value['y_coordinate'];
                    $ly_coordinate = $value['x_coordinate'];
                break;
            }

            if($value['reserve_code']){
                if(!array_key_exists($value['reserve_code'], $reserveInf)) {
                    $reserveInf[$value['reserve_code']]['total'] = 1;
                }else{
                    $reserveInf[$value['reserve_code']]['total']++;
                }
            }else{
                if(!array_key_exists($value['seat_class_id'], $typeInf)) {
                    $typeInf[$value['seat_class_id']]['total'] = 1;
                    $typeInf[$value['seat_class_id']]['sell'] = 0;
                }else{
                    $typeInf[$value['seat_class_id']]['total']++;
                }
                if(array_key_exists($value['alloc_seat_id'], $sellStatusData)){  
                    $typeInf[$value['seat_class_id']]['sell']++;
                }
            }
          
            if(!array_key_exists($value['floor_name'], $seatMap)) {
                $seatMap[$value['floor_name']]["floorTittle"] = $value['floor_name'];
                $seatMap[$value['floor_name']]["imageUrl"] = $value['floor_image_file_name'];
                $seatMap[$value['floor_name']]["blockData"] = [];
            }
            if(!array_key_exists($value['block_name'], $seatMap[$value['floor_name']]['blockData'])) {
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['blockTittle'] = $value['block_name'];
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['gate'] = $value['gate'];
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['direction'] = $value['seat_direction'];
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['seatData'] = [];
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x_min'] = $lx_coordinate;
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x_max'] = $lx_coordinate;
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y_min'] = $ly_coordinate;
                $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y_max'] = $ly_coordinate;

                switch ($value['seat_direction']) {
                    case 3: //←
                    case 4: //→
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum'][$ly_coordinate] = $value['seat_number'];
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line'][$lx_coordinate] = $value['seat_cols'];
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y'][$ly_coordinate] = $ly_coordinate;
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x'][$lx_coordinate] = $lx_coordinate;
                        break;
                    
                    case 1: //↑
                    case 2: //↓
                    default:
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line'][$ly_coordinate] = $value['seat_cols'];
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum'][$lx_coordinate] = $value['seat_number'];
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y'][$ly_coordinate] = $ly_coordinate;
                        $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x'][$lx_coordinate] = $lx_coordinate;
                        break;
                }

            }else{
                if($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x_min'] > $lx_coordinate){
                    $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x_min'] = $lx_coordinate;
                }
                if($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x_max'] < $lx_coordinate){
                    $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x_max'] = $lx_coordinate;
                }
                if($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y_min'] > $ly_coordinate){
                    $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y_min'] = $ly_coordinate;
                }
                if($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y_max'] < $ly_coordinate){
                    $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y_max'] = $ly_coordinate;
                }
                if(!in_array($value['seat_cols'], $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line'])){
                    switch ($value['seat_direction']) {
                        case 3: //←
                        case 4: //→
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line'][$lx_coordinate] = $value['seat_cols'];
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x'][$lx_coordinate] = $lx_coordinate;
                            arsort($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line']);
                            break;
                        case 1: //↑
                        case 2: //↓
                        default:
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line'][$ly_coordinate] = $value['seat_cols'];
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y'][$ly_coordinate] = $ly_coordinate;
                            krsort($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['line']);
                            break;
                    }
                }
                if(!in_array($value['seat_number'], $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum'])){
                    switch ($value['seat_direction']) {
                        case 3: //←
                        case 4: //→
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum'][$ly_coordinate] = $value['seat_number'];
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['y'][$ly_coordinate] = $ly_coordinate;
                            arsort($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum']);
                            break;
                        case 1: //↑
                        case 2: //↓
                        default:
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum'][$lx_coordinate] = $value['seat_number'];
                            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]['x'][$lx_coordinate] = $lx_coordinate;
                            arsort($seatMap[$value['floor_name']]["blockData"][$value['block_name']]['lineNum']);
                            break;
                    }
                }
            }
         
            if(array_key_exists($value['alloc_seat_id'], $sellStatusData)) {
                $sellStatus = true;
                $sellStatusId = $sellStatusData[$value['alloc_seat_id']]['seat_status'];
                $sellOrderId = $sellStatusData[$value['alloc_seat_id']]['order_id'];
            }else{
                $sellStatus = false;
                $sellStatusId = '';
                $sellOrderId = '';
            }
            
            if($value['reserve_code']){
                $type = 1;
                $typeId = $reserveSeatIdChange[$value['reserve_code']];
                $reserveData = $this->SellManageRepositories->getReserveData($value['reserve_code']);
               
                $seatInf = array(
                    'seat_id' => $value['seat_id'],
                    'alloc_seat_id' => $value['alloc_seat_id'],
                    'type' => $type,
                    'type_id' =>  $typeId,
                    'text' => $reserveData['reserve_symbol'],
                    'title' => $reserveData['reserve_name'],
                    'color' => $reserveData['reserve_word_color'],
                );

            }else{
                $type = 2;
                $typeId = $value['seat_class_id'];
               
                if($typeId){
                    $seatClassInf =  $this->SellManageRepositories->getSeatClassData($typeId);

                    $seatInf = array(
                        'seat_id' => $value['seat_id'],
                        'alloc_seat_id' => $value['alloc_seat_id'],
                        'type' => $type,
                        'type_id' => $typeId,
                        'text' => '',
                        'title' => $seatClassInf['seat_class_name'],
                        'color' => $seatClassInf['seat_class_color'],
                    );

                }else{
                    $type = 0;
                    $typeId = 0;

                    $seatInf = array(
                        'seat_id' => $value['seat_id'],
                        'alloc_seat_id' => $value['alloc_seat_id'],
                        'type' => '0',
                        'type_id' => '0',
                        'text' => '',
                        'title' => '',
                        'color' => '',
                    );
                }
            }
            
            $seatMap[$value['floor_name']]["blockData"][$value['block_name']]["seatData"][$ly_coordinate.'.'.$lx_coordinate] = array(
                'x' => $lx_coordinate,
                'y' => $ly_coordinate,
                'em' => $lx_coordinate,
                'number' => $value['seat_number'],
                'rowname' => $value['seat_cols'],
                'alloc_seat_id' => $value['alloc_seat_id'],
                'type' => $type,
                'num' => $typeId,
                'typeId' => $typeId,
                'typeData' =>  $seatInf,
                'sellStatus' => $sellStatus,
                'sellStatusId' => $sellStatusId,
                'sellOrderId' => $sellOrderId,
                'status' => 'N',
            );
        }
        
        foreach($seatType as $key => $value){
            $typeReserve = 0;
            $typeSell = 0;
            $typeTotal = 0;
            $stock_id = '';
            
            if(array_key_exists($value['seat_class_id'], $typeInf)){
                $typeSell = $typeInf[$value['seat_class_id']]['sell'];
                $typeTotal =  $typeInf[$value['seat_class_id']]['total'];
            }

            //seat_class_kbn = 2 : 自由席
            if($value['seat_class_kbn'] == 2){
                $freeSeatData = $this->SellManageRepositories->getFreeSeatData($value['seat_class_id'], $scheduleId);
                if(isset($freeSeatData[0])){
                    $typeTotal    = $freeSeatData[0]['stock_limit'];
                    $freeseat_sum += intval($typeTotal);
                    $stock_id     = $freeSeatData[0]['stock_id'];
                }
            }
          
            $sell_seat = $this->SellManageRepositories->getSeatClassIdData($value->seat_class_id, [3], $scheduleId);
            $sell_seat_sum = $sell_seat->count();

            $typeSeat[] = array(
                "index" => $key,
                'class_id' => $value->seat_class_id,
                'stock_id' => $stock_id,
                'seat_class_name' => $value->seat_class_name,
                'seat_class_name_short' => $value->seat_class_name_short,
                'seat_class_kbn' => $value->seat_class_kbn,
                'next_seat_flg' => $value->next_seat_flg,
                "type" => -1,
                'gate' => $value->gate,
                'disp_order' => $value->disp_order,
                'seat_class_color' => $value->seat_class_color,
                'typeReserve' => $typeReserve,
                'typeSell' => $sell_seat_sum,
                'typeTotal' => $typeTotal,
                'status' => 'N',
                'errorStatus' => false,
                'errorMsn' => '',
            );
            $seatTotal += $typeTotal;
            $seat_receive += $sell_seat_sum;
            $seatReserve += $typeReserve;
            $seatSelect += $typeSell;
        }
       
        $data = array(
            'publish_status'    => true,
            'date_value'        => -1,
            'rule_id'           => -1,
            'performanceId'     => $seatClassData->performance_id,
            'draftId'           => -1,
            'scheduleId'        => $scheduleId,
            'performanceName'   => $performanceData->performance_name,
            'performanceStatuc' => $performanceData->status,
            'date'              => $seatClassData->performance_date,
            'time'              => $seatClassData->start_time,
            'seatSelect'        => $seatSelect,
            'seatReserve'       => $seatReserve,
            'seatTotal'         => $seatTotal,
            'seat_receive'      => $seat_receive,
            'reserveData'       => $reserveSeat,//固定席位
            'typeSeat'          => $typeSeat,//一般席位
            'seatmap_profile_cd'=> $performanceData->seatmap_profile_cd,
            'seatMap'           => $seatMap,
            'upload_result'     => $upload_result,
        );
        
        $sellSeatMap = array(
            'status' => '',
            'data' => $data
        );
     
        return  $sellSeatMap;
    }
    /**
     * 取得未發布前席位資料
     * 
     * @parm array session_data
     * @return array
     */
    public function getDraftSeatMapData($session_data){
        $draft = $this->SellManageRepositories->getDraftData($session_data['draft_id']);
        $draft_info = json_decode($draft['draft_info']);
        $basis_data = $draft_info->basisData;
        $time_setting = $draft_info->timeSetting;
        $ticket_seeting = $draft_info->ticketSeeting;
        $ticket_type = $ticket_seeting->ticketSetting->settingType;
        if($ticket_type === 'freeSeat') {
            //全席自由
            $ticket_setting = [];
            if(isset($ticket_seeting->ticketSetting->data)) {
                array_push($ticket_setting,$ticket_seeting->ticketSetting->data);
            }
        }
        else {
            $ticket_setting = isset($ticket_seeting->ticketSetting->data)?$ticket_seeting->ticketSetting->data:[];//指定席
        }
        
        
        $spec_ticket_setting = isset($ticket_seeting->specTicketSetting->data)?$ticket_seeting->specTicketSetting->data:[];//保留席
        $map_data = $draft_info->mapData[0]->data[0]->mapData;
        $date_value = null;
        $time = null;
        $type_seat = [];
        $reserve_data = [];
        $performance_date = null;

        $upload_result = array(
            'upload'     => false,   
            'book_error' => false,
            'message'   => '',
            'seat_book' => []
        );
        
        if(session()->exists('update_result')){
            $update_result = session('update_result');
            $upload_result['upload'] = true;
         
            if($update_result['result']){
                $upload_result['book_error'] = false;
                $upload_result['message'] = $update_result['message'];
            }else{
                $upload_result['book_error'] = true;
                $upload_result['message'] = '更新できませんでした';//$update_result['message']; 
            }
        }

        foreach ($time_setting->calenderDate as $value){ 
            if($value->date->dateValue == $session_data['performance_date_timestamp']){
                foreach ($value->date->rule as $rule){
                    if($rule->id == $session_data['rule_id']){
                        $date_value = $value->date->dateValue;
                        $performance_date = date("Y-m-d",strtotime($rule->date));
                        $time = $rule->time;
                        break;
                    }
                }
            }
        }

        $seat_total = 0;

        // type-0=無， 1=保留席 2=指定席，3=自由 (席位類型，用於未發佈) 18-11-2020 T
        foreach ($ticket_setting as $key => $value){
            $seat_class_kbn = 1;
            $type = 2;
            if($ticket_type === 'freeSeat') {
                $seat_total2 = $value->seatQty;
                $seat_class_kbn = 2;
                $type = 3;
            }
            else {
                $seat_total2 = $value->seatTotal;
                if($value->seatFree){
                    $seat_class_kbn = 2;
                    $type = 3;
                }    
            }
            


            if(isset($value->respectiveData)){
                foreach($value->respectiveData as $respective){
                    //timestamp - php  秒 與 js 毫秒
                    if($respective->dateValue == $session_data['performance_date_timestamp'] && $respective->ruleId == $session_data['rule_id']){
                        $seat_total2 = $respective->total;
                        break;
                    }
                }
            }

            $type_seat[] = array(
                "index" => $key,
                "class_id" => "",
                "stock_id" => "",
                "seat_class_name" => $value->seatName,
                "seat_class_name_short" => '',
                "seat_class_kbn" => $seat_class_kbn,//1:指定席, 2:自由席, 3:引換券
                "next_seat_flg" => isset($value->seatNextSeat)?$value->seatNextSeat:0,
                "type" => $type,
                "gate" => "",
                "disp_order" => $value->seatid,
                "seat_class_color" => isset($value->seatColor)?$value->seatColor:0,
                "typeReserve" => 0,
                "typeSell" => 0,
                "typeTotal" => $seat_total2,
                "status" => $value->seatStatus,
                "errorStatus" => false,
                "errorMsn" => "",
            );
            $seat_total += $seat_total2;
            
        }
        foreach ($spec_ticket_setting as $key => $value){
            $type = 1;

            $reserve_data[] = array(
                "index" => $key,
                'reserve_code' => '',
                'reserve_name' => $value->ticketName,
                'color' => '#FFFFFF',
                'text' => $value->ticketText,
                'text_color' => $value->ticketColor,
                'order_total' => 0,
                "type" => $type,
                'total' => $value->ticketTotal,
                'status' => $value->ticketStatus,
            );
            $seat_total += (int)$value->ticketTotal;
        }
        
        $data = array(
            'publish_status'    => false,
            'date_value'        => $date_value,
            'rule_id'           => $session_data['rule_id'],
            'performanceId'     => $draft['performance_id'],
            'draftId'           => $session_data['draft_id'],
            'scheduleId'        => -1,
            'performanceName'   => $basis_data->eventTitle,
            'performanceStatuc' => $draft['new_status'],
            'date'              => $performance_date,
            'time'              => $time,
            'seatSelect'        => 0,
            'seatReserve'       => 0,
            'seatTotal'         => $seat_total,
            'seat_receive'      => 0,
            'reserveData'       => $reserve_data,//固定席位
            'typeSeat'          => $type_seat,//一般席位
            'seatmap_profile_cd'=> null,
            'seatMap'           => $map_data,
            'upload_result'     => $upload_result,
        );
        
        $sellSeatMap = array(
            'status' => '',
            'data' => $data
        );
     
        return  $sellSeatMap;
    }
    /**
     * 
     * 
     * 
     */
    public function getDetailData($scheduleId, $page = 1, $filterJson = null){
        $GLID                   = session('GLID');
        $account_cd             = session('account_cd');
        $performance_id         = session('performance_id');
        $performance_status     = session('performance_status');
        $disp_personal_info     = session('personal_info_flg')?false:true;
        $data                   = [];
        $reservationInf         = [];
        $allPie                 = 0;
        $allTicketPrice         = 0; 
        $allPriceCost           = 0;
        $received_amount_sum    = 0;
        $seatOption             = [];
        $ticketOption           = [];
        $freeSeatInt            = [];
        $csv                    = '';
        $order_status           = ['0', '1', '3'];
        $url                    = $scheduleId.'?filterJson='.$filterJson;

        if(session()->exists('insert_draw_result')){
            $insert_draw_result = true;
            $insert_draw_msn    = session('insert_draw_msn');
            session()->forget('insert_draw_result');
        }else{
            $insert_draw_result = false;
            $insert_draw_msn    = '';
        }
       
        if(session()->exists('resend_draw_result')){
            $resend_draw_result = true;
            session()->forget('resend_draw_result');
        }else{
            $resend_draw_result = false;
        }

        if(session()->exists('cancel_order_result')){
            $cancel_order_result = true;
            $cancel_order_status = session('cancel_order_result');
            $cancel_order_msn    = session('cancel_order_msn');
            session()->forget('cancel_order_result');
        }else{
            $cancel_order_result = false;
            $cancel_order_status = true;
            $cancel_order_msn    = '';
        }
       
        if(session()->exists('revise_amount_result')){
            $revise_amount_result = true;
            $revise_amount_msn    = session('revise_amount_msn');
            session()->forget('revise_amount_result');
        }else{
            $revise_amount_result = false;
            $revise_amount_msn    = '';
        }
       
        $afilterData = $this->getOrderFilterInf($filterJson);
        $order_status = $afilterData['order_status'];
        $filter = $afilterData['filter'];

        $data = array(
            'scheduleId' => $scheduleId,
            'filter'     => $filter,
            'filterData' => $afilterData,
        );
        
        $performanceData = $this->SellManageRepositories->getdetailData($scheduleId);
        $reservationId   = $this->SellManageRepositories->getGeneralReservationId($data);
        $performance_end = Carbon::parse($performanceData['performance_date'])->addDay()->isPast();
      
        if($performanceData){
            $performance_id     = $performanceData["performance_id"];
            $seatmap_profile_cd = ($performanceData["sch_kbn"] == 0 )?null:$performanceData["seatmap_profile_cd"];
            $GLID               = $performanceData["GLID"];
        }
       
        $filterData = array(
            'performance_id' => $performance_id
        );
       
        //取得票種票別名稱
        $seatData       = $this->SellManageRepositories->getSeatTicketType($filterData);
        $seatOption     = $seatData['seatsTittle'];
        $ticketOption   = $seatData['ticketsTittle'];

        //フリーアンケート 2021/04/09 LS-Itabashi
        $questionnaires = $this->SellManageRepositories->getQuestion($performance_id);

        //一般訂單
        if($afilterData['seatOrder'] || $afilterData['seatFree']){         
            $freeSeatFilterData = array(
                'performance_id' => $performance_id,
            );
          
            $freeSeatData = $this->SellManageRepositories->getFreeSeatData1($freeSeatFilterData);
        
            foreach($freeSeatData as $value){
                $inf = array(
                    'GLID'              => $GLID,
                    'schedule_id'       => $scheduleId,
                    'seat_class_id'     => $value['seat_class_id'],
                    'seatTitle'         => $value['seat_class_name'],
                );

                $freeSeatInt[] = array(
                    'inf'   => $inf,
                );
            }

            $reservation = $this->SellManageRepositories->getGeneralReservationInf($reservationId);
            
            foreach($reservation as $order){
                // STS 2021/08/13 task 45 -- START
                $checkData = $order['member_id'];
                if($checkData == 'gettiis$[N_M]') {
                    $tel = '';
                    $email = '';
                    $allow_email = '';
                    $status = '';
                } else {
                $newMember_id = substr($checkData, 8);
                $dataMember = $this->MemberRepositories->getMemberData($newMember_id);


                if($dataMember) {
                    
                    foreach($dataMember as $test){
                    if ($test['system_kbn'] === 1) {
                         if ($test['status'] == 2) {
                        $tel = $test['tel_num'];
                        $email = $test['mail_address'];
                        $allow_email = $test['allow_email'];
                       
                        }  else {
                            $tel ='';
                            $email = '';
                            $allow_email = '';
                        }  
                    } else {
                         $tel ='';
                        $email = '';
                        $allow_email = '';
                    }
                           
                }
                } else{
                  $tel = '';
                  $email = '';
                  $allow_email = '';
                }
                }
               // STS 2021/08/13 task 45 -- END
                $totalPie               = 0;
                $totalPiece             = 0;
                $reservation_commission = $order['commission_sv']+$order['commission_payment']+$order['commission_ticket']+$order['commission_delivery']+$order['commission_sub']+$order['commission_uc'];
                $allCost                = $reservation_commission;
                $parentCsv              = $order['reserve_no'].','.$order['reserve_date'].','.$order['member_id'].','.$order['consumer_name'].','.$order['consumer_kana'].','.$order['tel_num'].','.$order['mail_address'].','.$order['pay_method'].','.$order['pickup_method'].','.$order['reserve_no'].',';
                $issue_flg              = [];
                $payment_flg            = [];
                $visit_flg              = [];
                $order_seat_type        = []; 
                $seatTypeAll            = [];
                $ticketTypeAll          = [];
                $data                   = [];
                $all_visit_flg          = true;
                $all_visit_flg2         = true;

                $seat_sale = $order['seat_sale'];

                foreach($seat_sale as $value){
                  
                    $seat_class_id = $value['seat_class_id'];

                    if($value['alloc_seat_id']){
                        $seatData =  $value['seat']['stage_seats'];

                        if($seat_sale[0]['seat']){                      
                            $seatPositionData = $value['seat'];
                            $floor_name     = $seatPositionData['hall_seat']['floor']['floor_name'];
                            $block_name     = $seatPositionData['hall_seat']['block']['block_name'];
                            $seat_cols      = $seatPositionData['hall_seat']['seat_cols'];
                            $seat_number    = $seatPositionData['hall_seat']['seat_number'];
                            $seatPosition   = $floor_name . '-' . $block_name . '-' . $seat_cols . '-' . $seat_number;
                        }
                        
                        $seat_class = $value['seat_class']['seat_class_kbn'];
                    }
                    else {
                        $seat_class_id = $value['reserve_code'];
                        $floor_name     = '';
                        $block_name     = '';
                        $seat_cols      = '';
                        $seat_number    = $value['seat_seq'];
                        $seatPosition   = '';
                        // TODO 0713 James : 暫時定義，需確認
                        $seat_class = 'R';
                    }
                    
                    $order_seat_type[] = $seat_class;
                    $seat_commission_sum = $value['commission_sv'] + $value['commission_payment'] + $value['commission_ticket'] + $value['commission_delivery'] + $value['commission_sub'] + $value['commission_uc'];
                    $sale_price_sum = $value['sale_price'] + $seat_commission_sum;
                   
                    //0:未1:済 日本 7-11 && 無卷 = -
                    if(
                        ($order['pickup_method'] === \Config::get('constant.pickup_method.store') && !$value['visit_date'])||
                        ($order['pickup_method'] === \Config::get('constant.pickup_method.no_ticketing') && !$value['visit_date'])
                    ){
                        $visit_flg = '-';
                    }else{
                        if($value['visit_flg']){
                            $visit_flg = '済';
                            $all_visit_flg2 = false;
                        }else{
                            $visit_flg = '未';
                            $all_visit_flg = false;
                        }
                       
                    }
              
                    $data[] = array(
                        'seat_sale_id'          => $value['seat_sale_id'],
                        'seatTitle'             => $value['seat_class_name'],
                        'ticketTitle'           => $value['ticket_class_name'],
                        'seatType'              => $seat_class,
                        'floor_name'            => $floor_name,     
                        'block_name'            => $block_name,    
                        'seat_cols'             => $seat_cols,     
                        'seat_number'           => $seat_number,  
                        'seatPosition'          => $seatPosition,
                        'seat_price'            => sprintf("%.0f",$value['sale_price']),
                        'seat_commission_sum'   => sprintf("%.0f", $seat_commission_sum),
                        'price'                 => sprintf("%.0f",$sale_price_sum),
                        'inf'                   => NULL,
                        'orderInf'              => NULL,
                        'visit_flg'             => $visit_flg,
                        'visit_date'        => (isset($value['visit_date'])?$value['visit_date']:''),
                        'visit_gate'        => (isset($value['visit_gate'])?$value['visit_gate']:''), //STS 2021/09/01 Task 49
                        'seat_seq'              => $value['seat_seq'],
                    );
                        
                    $payment_flg[] = $value['payment_flg'];
                    $seatTypeAll[] = str_replace(' ', '', $value['seat_class_name']);
                    $ticketTypeAll[] = str_replace(' ', '', $value['ticket_class_name']);
                    $issue_flg[] = $value['issue_flg'];
                    //$visit_flg[] = $value['visit_flg'];
                    $totalPie++;
                    $totalPiece += $sale_price_sum;
                    $allCost += $sale_price_sum;
                    $csv .= $parentCsv.$value['seat_class_name'].','.$value['ticket_class_name'].','.$seat_class.','.$seatPosition.','.$sale_price_sum.'\n';
                }
                
                if(count($issue_flg) >= 1){
                    $order_issue = $issue_flg[0];
                }
                                
                for($num = 1; $num < count($issue_flg); $num++){
                    if($issue_flg[$num] !== $issue_flg[0]){
                        $order_issue = 2;
                        break;
                    }
                }

                // if(count($visit_flg) >= 1){
                //     $order_visit = $visit_flg[0];
                // }
                
                // for($num = 1; $num < count($visit_flg); $num++){
                //     if($visit_flg[$num] !== $visit_flg[0]){
                //         $order_visit = 2;
                //         break;
                //     }
                // }
                
                if(count($payment_flg) >= 1){
                    $order_payment  = $payment_flg[0];
                }

                //如果其余票付款不相同，付款狀態就未完成
                for($num = 1; $num < count($payment_flg); $num++){
                    if($payment_flg[$num] !== $payment_flg[0]){
                        $order_payment = 2;
                        break;
                    }
                }
               
                //判斷訂單是否有效
                $order_status_inf = $this->getOrderStatus($order);
                
                $filter_data = array(
                    'order_id' => $order['order_id'],
                );
               
                $revise_amount = $order['amount_revise'];
                $revise_amount_data = array(
                    'status'                => false,
                    'order_id'              => '',
                    'amount_status'         => '',
                    'amount_total'          => '',
                    'revise_info'           => '',
                    'amount_memo'           => '',
                    'update_account'        => '',
                    'created_at'            => '',
                );
                if(!is_null($revise_amount)){
                    $revise_amount_data['status']           = true;
                    $revise_amount_data['order_id']         = $revise_amount['order_id'];
                    $revise_amount_data['amount_status']    = $revise_amount['amount_status'];
                    $revise_amount_data['amount_total']     = intval($revise_amount['amount_total']);
                    $revise_amount_data['revise_info']      = json_decode($revise_amount['revise_info'], true);
                    $revise_amount_data['amount_memo']      = $revise_amount['amount_memo'];
                    $revise_amount_data['update_account']   = $revise_amount['user_account']['account_code'];
                    $revise_amount_data['created_at']       = $revise_amount['created_at'];
                }
                
                //判斷是否可調整訂單金額
                $revise_amount = array(
                    'reviseStatus'  => false,
                    'data'          => $revise_amount_data,
                );
                
                if(!$order_status_inf['order_status_cancel'] && !$revise_amount_data['status']){
                    switch ($order['pay_method']) {
                        case \Config::get('constant.pay_method.card'):
                            if($order_payment != 1){
                                $revise_amount['reviseStatus'] = true;
                            }
                            break;
                        case \Config::get('constant.pay_method.store'):
                            if($order_payment != 0){
                                $revise_amount['reviseStatus'] = true;
                            }
                            break;

                    }
                }
                $refund_inf = [];

                if($order['cancel_order']){
                    $refund_inf = array(
                        'refund_kbn'        => $order['cancel_order']['refund_kbn'],
                        'refund_inf'        => $order['cancel_order']['refund_inf'],
                        'refund_payment'    => $order['cancel_order']['refund_payment'],
                        'refund_due_date'   => $order['cancel_order']['refund_due_date'],
                    );
                }
            
                $received_amount = 0;

                if($order_payment){
                    $received_amount = $allCost;
                }
              
                if($order['cancel_flg']){
                    $cancel_order_data = $order['cancel_order'];
                    
                    if($order['cancel_order']){
                        $received_amount = $allCost - intval($order['cancel_order']['refund_payment']);
                    }
                }
                if($order['cancel_order']){
                  //cancel済
                  $received_amount = $received_amount - intval($order['use_point']);
                }

                //場次結束後,已完成付款的訂單皆不可取消
                if($performance_end && $order_payment == 1){
                    $cancel_able = false;
                }else{
                    $cancel_able = true;
                }
               
                $all_visit_flg_str = '済※';
                if($all_visit_flg && $all_visit_flg2) {
                    $all_visit_flg_str = '-';
                }
                else if($all_visit_flg) {
                    $all_visit_flg_str = '済';
                }
                else if($all_visit_flg2) {
                    $all_visit_flg_str = '未';
                }

                //フリーアンケート回答 2021/04/09 LS-Itabashi
                $answers = [];
                foreach($order['question_answer'] as $answer){
                    $answers[$answer['question_id']] = $answer['answer_text'];
                }

                if(in_array($order_status_inf['order_cancel_type'], $order_status)){
                    $reservationInf[] = array(
                        'order_id'                  => $order['order_id'],
                        'order_type'                => self::NORMAL_ORDER,
                        'reserve_no'                => $order['reserve_no'],
                        'reserve_date'              => $order['reserve_date'],
                        'member_id'                 => $order['member_id'],
                        'consumer_name'             => GLHelpers::hideInformation($order['consumer_name']),
                        'consumer_kana'             => $order['consumer_kana'],
                        'tel_num'                   => GLHelpers::hideInformation($order['tel_num']),
                        'mail_address'              => GLHelpers::hideInformation($order['mail_address'], 'email'),
                        'pay_method'                => $order['pay_method'],
                        'pickup_method'             => $order['pickup_method'],
                        'issue_flg'                 => $order_issue,
                        'visit_flg'                 => $all_visit_flg_str,
                        'payment_flg'               => $order_payment,
                        'order_seat_type'           => $order_seat_type,
                        'reserve_no'                => $order['reserve_no'],
                        'total_pie'                 => $totalPie,
                        'total_price'               => $totalPiece,
                        'allCost'                   => $allCost,
                        'received_amount'           => $received_amount,
                        'reservation_commission'    => $reservation_commission, 
                        'seatData'                  => $data,
                        'seatType'                  => array_unique($seatTypeAll),
                        'ticketType'                => array_unique($ticketTypeAll),
                        'cancel_flg'                => $order['cancel_flg'],
                        'cancel_able'               => $cancel_able,
                        'order_status_cancel'       => $order_status_inf['order_status_cancel'],
                        'order_cancel_reason'       => $order_status_inf['order_cancel_reason'],
                        'refund_inf'                => $refund_inf, 
                        'revise_amount'             => $revise_amount,
                        'commission_payment'        => $order['commission_payment'],
                        'commission_ticket'         => $order['commission_ticket'],
                        'use_point'                 => $order['use_point'],
                        'questionAnswers'           => $answers,
                        // STS 2021/08/13 task 45 -- START
                        'tel'                       => isset($tel)?$tel:'',
                        'email'                     => isset($email)?$email:'',
                        'allow_email'               => isset($allow_email)?$allow_email:''
                        // STS 2021/08/13 task 45 -- END
                    );

                    $received_amount_sum  += $received_amount;
                    $allPie               += $totalPie;
                    $allTicketPrice       += $totalPiece;
                    $allPriceCost         += ($revise_amount['data']['status'])?(int)$revise_amount['data']['amount_total']:$allCost;
                }
            }
        }
        
        //自由席
        if($afilterData['seatFree'] && in_array(2, $afilterData['receipt'])  && in_array(\Config::get('constant.pay_method.not'), $afilterData['pay_method'])){
            //自由席訂單資料資料（免費）
            $filterData = array(
                'scheduleId'    => $scheduleId,
                'filter'        => $filter,
                'filterData'    => $afilterData,
            );
            $nonReservedSeatOrder = $this->SellManageRepositories->getFreeSeatReservationData($filterData);
            foreach($nonReservedSeatOrder as $value){
                $data   = [];
                $filterData =  array(
                    'scheduleId'    => $scheduleId,
                    'order_id'      => $value['order_id'],
                );
                $nonReservedSeatData =  $value['seat_sale'];
                $all_visit_flg = true;
                $all_visit_flg2 = true;

                //票資料整理
                foreach($nonReservedSeatData as $perValue){
                    $memberId = str_replace("gettiis$", "", $value['member_id']);
        
                    $resInf = array(
                        'GLID'           => $GLID,
                        'performance_id' => $performance_id,
                        'schedule_id'    => $scheduleId,
                        'alloc_seat_id'  => null,
                    );
                   
                    if(is_null($value['order_id'])){
                        $orderStatus = 1;
                    }else{
                        $orderStatus = 2;
                    }
                
                    $orderInf = array(
                        'status'        => $orderStatus,
                        'order_id'      => $value['order_id'],
                        'pickup_method' => $value['pickup_method'],
                        'mail_address'  => $value['mail_address'],
                        'tel_num'       => $value['tel_num'],
                    );
                    
                    //0:未1:済 日本 7-11 = -
                    if($value['pickup_method'] === \Config::get('constant.pickup_method.store')){
                        $visit_flg = '-';
                    }else{
                        if($perValue['visit_flg']){
                            $visit_flg = '済';
                            $all_visit_flg2 = false;
                        }else{
                            $visit_flg = '未';
                            $all_visit_flg = false;
                        }
                       
                    }

                    $data[] = array(
                        'memberId'          => $memberId,
                        'consumerName'      => $value['consumer_name'],
                        'seatTitle'         => $perValue['seat_class_name'],
                        'ticketTitle'       => '-',
                        'seatType'          => 2,
                        'floor_name'        => 0,     
                        'block_name'        => 0,    
                        'seat_cols'         => 0,     
                        'seat_number'       => 0, 
                        'seatPosition'      => null,
                        'visit_flg'         => $visit_flg,
                        'visit_date'        => (isset($value['visit_date'])?$value['visit_date']:''),
                        'visit_gate'        => (isset($value['visit_gate'])?$value['visit_gate']:''), //STS 2021/09/01 Task 49
                        'price'             => '-',
                        'inf'               => $resInf,
                        'orderInf'          => $orderInf,
                        'seat_seq'          => $perValue['seat_seq'],
                    );
                    
                }
                
                //訂單金額修改格式（官方自由席無使用）統一資料格式
                $revise_amount_data = array(
                    'status'                => false,
                    'order_id'              => '',
                    'amount_status'         => '',
                    'amount_total'          => '',
                    'amount_memo'           => '',
                    'update_account'        => '',
                    'created_at'            => '',
                );
                $revise_amount = array(
                    'reviseStatus'   => false,
                    'data'            => $revise_amount_data,
                );
                $refund_inf = array(
                    'refund_kbn'        => "",
                    'refund_inf'        => "",
                    'refund_payment'    => "",
                    'refund_due_date'   => "",
                );
                //判斷訂單是否有效
                $order_status_cancel = false;
                $order_cancel_reason = '';

                //訂單取消類型 0:無 1：一般取消 2：逾時取消
                $order_status_inf = $this->getOrderStatus($value);

                $all_visit_flg_str = '済※';
                if($all_visit_flg && $all_visit_flg2) {
                    $all_visit_flg_str = '-';
                }
                else if($all_visit_flg) {
                    $all_visit_flg_str = '済';
                }
                else if($all_visit_flg2) {
                    $all_visit_flg_str = '未';
                }

                if(in_array($order_status_inf['order_cancel_type'], $order_status)){
                    $reservationInf[] = array(
                            'order_id'              => $value['order_id'],
                            'order_type'            => self::NONRES_ORDER,
                            'reserve_no'            => $value['reserve_no'],
                            'reserve_date'          => $value['reserve_date'],
                            'member_id'             => $memberId,
                            'consumer_name'         => GLHelpers::hideInformation($value['consumer_name']),
                            'consumer_kana'         => '',
                            'tel_num'               => GLHelpers::hideInformation($value['tel_num']),
                            'mail_address'          => GLHelpers::hideInformation($value['mail_address'], 'email'),
                            'pay_method'            => 4,
                            'visit_flg'             => $all_visit_flg_str,
                            'pickup_method'         => $value['pickup_method'],
                            'issue_flg'             => 4,
                            'payment_flg'           => 4,
                            'order_seat_type'       => null,
                            'total_pie'             => count( $data),
                            'total_price'           => '-',
                            'allCost'               => '-',
                            'received_amount'       => 0,
                            'seatData'              => $data,
                            'seatType'              => null,
                            'ticketType'            => null,
                            'cancel_flg'            => $value['cancel_flg'],
                            'refund_inf'            => [], 
                            'order_status_cancel'   => $order_status_inf['order_status_cancel'],
                            'order_cancel_reason'   => $order_status_inf['order_cancel_reason'],
                            'refund_inf'            => $refund_inf, 
                            'revise_amount'         => $revise_amount,
                            'questionAnswers'       => []
                        );
                }
            }
        }

        //保留席
        if($afilterData['seatReserve'] && in_array(2, $afilterData['receipt']) && in_array(\Config::get('constant.pay_method.not'), $afilterData['pay_method'])){
            $filterData = array(
                'performance_id' => $performance_id,
                'scheduleId'     => $scheduleId,
                'filter'         => $filter,
                'filterData'     => $afilterData,
            );
            $performanceReservationData = $this->SellManageRepositories->getReservationSeatData($filterData);
            $reservationData = [];
            $reservationSort = [];
            foreach($performanceReservationData as $value){
                $floor_name     =   '';     
                $block_name     =   '';
                $seat_cols      =   '';
                $seat_number    =   '';
                $ticketName     =   '';
   
                if($value['seat_id']){
                    $seatPositionData = $value['hall_seat'];
                    $floor_name     = $seatPositionData['floor']['floor_name'];
                    $block_name     = $seatPositionData['block']['block_name'];
                    $seat_cols      = $seatPositionData['seat_cols'];
                    $seat_number    = $seatPositionData['seat_number'];
                    $seatPosition   = $floor_name . '-' . $block_name . '-' . $seat_cols . '-' . $seat_number;
                }
            
                if($value['seat_class_id']){
                    $ticketResult = $value['seat_class']['ticket_class'];
                    if($ticketResult){
                        $ticketName = $ticketResult[0]['ticket_class_name'];
                    }
                }
               
                if($value['schedule_id'] && $value['schedule_id'] == $scheduleId){
                    if(is_null($value['stage_seat_class_id']) && !is_null($value['stage_reserve_code'])){
                        $reserveData = $this->SellManageRepositories->getReserveData($value['stage_reserve_code']);
                    }else{
                        continue;
                    }
                }else{
                    $reserveData = $this->SellManageRepositories->getReserveData($value['reserve_code']);               
                }
            
                // $memberId = str_replace("gettiis$", "", $value['member_id']);
                $memberId = $value['member_id'];

                $resInf = array(
                    'GLID'           => $GLID,
                    'performance_id' => $performance_id,
                    'schedule_id'    => $scheduleId,
                    'alloc_seat_id'  => $value['alloc_seat_id'],
                );
                
                if(is_null($value['order_id'])){
                    $orderStatus = 1;
                }else{
                    $orderStatus = 2;
                } 
                
                $orderInf = array(
                    'status'        => $orderStatus,
                    'order_id'      => $value['order_id']?:'',
                    'pickup_method' => $value['pickup_method']?:'',
                    'mail_address'  => $value['mail_address']?:'',
                    'tel_num'       => $value['tel_num']?:'',
                    'reserve_no'    => $value['reserve_no']?:'',
                    'reserve_seq'   => $value['reserve_seq']?:'',
                    'issue_flg'     => $value['issue_flg'],
                );
           
                $order_status_inf = $this->getOrderStatus($value);
              
                if(in_array($order_status_inf['order_cancel_type'], $order_status)){
                    $reservationData[] =  array(
                        'reserve_name'          => $reserveData['reserve_name'],
                        'member_id'             => $memberId,
                        'consumer_name'         => $value['consumer_name'],
                        'ticketName'            => $ticketName,
                        'floor_name'            => $floor_name,     
                        'block_name'            => $block_name,    
                        'seat_cols'             => $seat_cols,     
                        'seat_number'           => $seat_number,
                        'seatPosition'          => $seatPosition,
                        'reserveData'           => $reserveData,
                        'visit_flg'             => $value['visit_flg'],
                        'visit_date'             => $value['visit_date'],
                        'visit_gate'             => $value['visit_gate'], //STS 2021/09/01 Task 49
                        'inf'                   => $resInf,
                        'orderInf'              => $orderInf,
                        'reserve_date'          => $value['reserve_date'],
                        'cancel_flg'            => $value['cancel_flg'],
                        'order_status_cancel'   => $order_status_inf['order_status_cancel'],
                        'order_cancel_reason'   => $order_status_inf['order_cancel_reason'],
                        'seat_seq'              => $value['seat_seq'],
                        'reserve_code'          => $reserveData['reserve_code'],
                    );
                }
            }
            foreach($reservationData as $value){
                if(array_key_exists('reserve_name', $value)){ 
                    $reservationSort[$value['reserve_code']][] = $value;
                }
            }
            ksort($reservationSort);
 
            foreach($reservationSort as $key => $value){
                $order_seat_type    = [];
                $seatTypeAll        = [];
                $ticketTypeAll      = [];
                $data               = [];
                $pickup_method      = '';
                $order_id           = '';
                $reserve_name       = '';
                $all_visit_flg      = true;
                $all_visit_flg2     = true;

                foreach($value as $perData){      
                    $memberId       = str_replace("gettiis$", "", $perData['member_id']);
                    $order_id       = $perData['orderInf']['order_id'];
                    $cancel_flg     = $perData['cancel_flg'];
                    $reserve_name   = $perData['reserve_name'];
                
                    if(
                        is_null($perData['visit_flg']) ||
                        ($perData['orderInf']['pickup_method'] === \Config::get('constant.pickup_method.store') && !$perData['visit_date']) ||
                        ($perData['orderInf']['pickup_method'] === \Config::get('constant.pickup_method.no_ticketing') && !$perData['visit_date'])
                    ){
                        $visit_flg = '-';
                    }else{
                        if($perData['visit_flg']){
                            $visit_flg = '済';
                            $all_visit_flg2 = false;
                        }else{
                            $visit_flg = '未';
                            $all_visit_flg = false;
                        }
                    }

                    $data[] = array(
                        'memberId'          => $perData['member_id']?:'',
                        'consumerName'      => $perData['consumer_name']?:'',
                        'seatTitle'         => $perData['reserve_name']?:'',
                        'ticketTitle'       => '',
                        'seatType'          => 3,
                        'floor_name'        => $perData['floor_name']?:'',
                        'block_name'        => $perData['block_name']?:'',
                        'seat_cols'         => $perData['seat_cols']?:'',
                        'seat_number'       => $perData['seat_number']?:'',
                        'seatPosition'      => $perData['seatPosition']?:'',
                        'price'             => '',
                        'visit_flg'         => $visit_flg?:'',
                        'visit_date'        => $perData['visit_date']?:'',
                        'visit_gate'        => $perData['visit_gate']?:'', //STS 2021/09/01 Task 49
                        'inf'               => $perData['inf'],
                        'orderInf'          => $perData['orderInf'],
                        'reserve_date'      => $perData['reserve_date']?:'',
                        'seat_seq'          => $perData['seat_seq']?:'0',
                        
                    );
                }
                
                $order_seat_type[] = 3;
                $seatTypeAll[] = $perData['reserve_name'];
                $ticketTypeAll[] = $perData['ticketName'];
            
                //訂單金額修改格式（保留席無使用）統一資料格式
                $revise_amount_data = array(
                    'status'                => false,
                    'order_id'              => '',
                    'amount_status'         => '',
                    'amount_total'          => '',
                    'amount_memo'           => '',
                    'update_account'        => '',
                    'created_at'            => '',
                );
                $revise_amount = array(
                    'reviseStatus'   => false,
                    'data'            => $revise_amount_data,
                );
                $refund_inf = array(
                    'refund_kbn'        => "",
                    'refund_inf'        => "",
                    'refund_payment'    => "",
                    'refund_due_date'   => "",
                );

                $all_visit_flg_str = '済※';
                if($all_visit_flg && $all_visit_flg2) {
                    $all_visit_flg_str = '-';
                }
                else if($all_visit_flg) {
                    $all_visit_flg_str = '済';
                }
                else if($all_visit_flg2) {
                    $all_visit_flg_str = '未';
                }
                $reservationInf[] = array(
                    'order_id'              => $order_id, 
                    'order_type'            => self::RES_ORDER,
                    'reserve_no'            => $reserve_name ,
                    'reserve_date'          => '-',
                    'member_id'             => null,
                    'consumer_name'         => '-',
                    'consumer_kana'         => '',
                    'tel_num'               =>'-',
                    'mail_address'          => '-',
                    'pay_method'            => 4,
                    'pickup_method'         => 4,
                    'issue_flg'             => 4,
                    'payment_flg'           => 4,
                    'visit_flg'             => $all_visit_flg_str,
                    'order_seat_type'       => $order_seat_type,
                    'total_pie'             => count( $data),
                    'total_price'           => '-',
                    'allCost'               => '-',
                    'received_amount'       => 0,
                    'seatData'              => $data,
                    'seatType'              => array_unique($seatTypeAll),
                    'ticketType'            => array_unique($ticketTypeAll),
                    'cancel_flg'            => $cancel_flg,
                    'order_status_cancel'   => false,
                    'order_cancel_reason'   => '',
                    'refund_inf'            => $refund_inf, 
                    'revise_amount'         => $revise_amount,
                    'questionAnswers'       => []
                );
                
                $allPie += count( $data);
            }
        } 
        if(!is_null($reservationInf)){
            $totalEvent = count($reservationInf);
            $nowPageStar = ($page - 1) * self::PAGE_SIZE;
            $nowPageEnd = self::PAGE_SIZE;
            $pageData = array_slice($reservationInf, $nowPageStar, $nowPageEnd);
            $paginator = new LengthAwarePaginator($pageData, $totalEvent, self::PAGE_SIZE);
            $paginator->withPath($url);
        }else{
            $pageData = $reservationInf;
            $paginator = null;
        }
        
        $performance_disp_status = $this->getPerformanceDispStatus($performanceData, $performanceData['performance_date']);

        $result = array(
            'performanceId'             => $performance_id,
            'performance_status'        => $performance_status,
            'performance_disp_status'   => $performance_disp_status,
            'seatmap_profile_cd'        => $seatmap_profile_cd,
            'scheduleId'                => $scheduleId,
            'page'                      => $page,
            'perfomanceTitle'           => $performanceData['performance_name'],
            'openDate'                  => $performanceData['performance_date'],
            'sch_kbn'                   => $performanceData['sch_kbn'],
            'openTime'                  => Carbon::parse($performanceData['start_time'])->format('H:i'),
            'status'                    => $performanceData['status'],
            'allPie'                    => $allPie,
            'allTicketPrice'            => $allTicketPrice, 
            'allPriceCost'              => $allPriceCost,
            'received_amount_sum'       => $received_amount_sum,
            'reservationData'           => $pageData,
            'all_reservation_data'      => $reservationInf,
            'seatOption'                => $seatOption,
            'ticketOption'              => $ticketOption,
            'csv'                       => $csv,
            'paginator'                 => $paginator,
            'insert_draw_result'        => $insert_draw_result,
            'insert_draw_msn'           => $insert_draw_msn,
            'resend_draw_result'        => $resend_draw_result,
            'cancel_order_result'       => $cancel_order_result ,
            'cancel_order_status'       => $cancel_order_status ,
            'cancel_order_msn'          => $cancel_order_msn,
            'revise_amount_result'      => $revise_amount_result,
            'revise_amount_msn'         => $revise_amount_msn,
            'freeSeatInt'               => $freeSeatInt,
            'filterJson'                => $filterJson,
            'questionnaires'            => $questionnaires,
        );
        return $result;
    }
    /**
     * get Performance Data
     * @param getPerformanceData
     * @return $result
     */
    public function getPerformanceData(array $request=null)
    {   
        $GLID = session('GLID');
        $admin_flg = session('admin_flg');
        $account_cd = session('account_cd');
       
        $keyword = null;
        $filterStatus = array('4','5','6','7','8');
        if(!empty($request)){
          $keyword = $request["keyword"];
          $filterStatus = (!empty($request["statusSelect"]))?$request["statusSelect"]:null;         
        }

        $statusHad = (empty($request))?array('0','0','0','0','0','0','0','0'):array('0','0','0','1','1','1','1','1');

        if(!empty($filterStatus))
        {
          foreach($filterStatus as $value)
          {
            $statusHad[$value-1] = "0";
          }
        }
        
        $GLID = ($GLID == 1)?'':$GLID;

        $filter_data = array(
            'GLID' => $GLID,
            'admin_flg' => $admin_flg,
            'account_cd' => $account_cd,
            'keyword' => $keyword,
            'filter_status' => $filterStatus,
        );

        
        $data = $this->SellManageRepositories->getPerformationSellInf($filter_data);
        
        $csv =  trans('sellManage.S_EventTitle').','.
                trans('sellManage.S_EventStatus').','.
                trans('sellManage.S_EventSeatTotal').','.
                trans('sellManage.S_EventFreeTotal').','.
                trans('sellManage.S_EventOnPorcessTotal').','.
                trans('sellManage.S_EventOnPorcessTotalFree').','.
                trans('sellManage.S_EventSellTotal').','.
                trans('sellManage.S_EventFreeTotal').','.
                trans('sellManage.S_EventNoSellTotal').','.
                trans('sellManage.S_EventNoSellFreeTotal').','.
                trans('sellManage.S_EventRestOfSeat').','.
                trans('sellManage.S_EventMaxTotal').
                '\n';
                
        if($data){
            foreach($data as $item){
                $disp_status = $this->getPerformanceDispStatusStr($item['disp_status']);
                $unsell_seat = strval(intval($item['SALE'])-intval($item['cnt_inpay_rev'])-intval($item['cnt_sale_rev']));

                if($item['stock_limit'] === 0 && $item['sch_kbn'] != 1 ){
                    $stock_limit = trans('common.S_Unlimited');
                    $unsell_free = trans('common.S_Unlimited');    
                }else {
                    $stock_limit = $item['stock_limit'];
                    $unsell_free = strval(intval($item['stock_limit'])-($item['cnt_inpay_free'])-($item['cnt_sale_free']));
                }

                $csv .= $item['performance_name'].','.
                        $disp_status.','.
                        $item['SALE'].','.
                        $stock_limit.','.
                        $item['cnt_inpay_rev'].','.
                        $item['cnt_inpay_free'].','.
                        $item['cnt_sale_rev'].','.
                        $item['cnt_sale_free'].','.
                        $item['RES'].','.
                        $unsell_seat.','.
                        $unsell_free.','.
                        $item['subtotal'].
                        '\n';
            }
        }else{
            $data = [];
        }

        $allPerformationData = array(
            'keyword' => $keyword,
            'filterStatus' => $statusHad,
            'csv' => $csv,
            'data' => $data,
        );

        return $allPerformationData;
    }
   
    //2021-06-23 STS - TASK 24: --START--
    /**
     * Get all schedules of published performance
    */
    public function getPerfomanceSchedules($performanceId){
        $this->SellManageRepositories->getPerformance($performanceId);
        $schedule_inf = $this->SellManageRepositories->getScheduleDateTimeInf();
            $result = array(
               'schedule_inf' => $schedule_inf
            );
            return $result;
    }
    /**
     * Get all schedules of unpublished performance
    */
    public function getPerfomanceUnpublish($performanceId){
        $this->SellManageRepositories->getPerformance($performanceId);
        $schedule_inf = $this->SellManageRepositories->getUnpublishDateTimeInf();
            $result = array(
               'schedule_inf' => $schedule_inf
            );
            return $result;
    }
    ////2021-06-23 STS - TASK 24 ----END-----

    public function getStageData($performanceId){
        $this->SellManageRepositories->getPerformance($performanceId);
        $performance_inf = $this->SellManageRepositories->getPerformanceSellInf();
        $schedule_inf = $this->SellManageRepositories->getScheduleSellInf($performanceId);
        //STS task 25 2020/06/24 start
       
        // $csv = trans('sellManage.S_EventOpenDate').','.    //活動日期
        //        trans('sellManage.S_EventOpenTime').','.    //場次時間
        //        trans('sellManage.S_EventTimeSlot').','.    //場次名稱
        //        trans('sellManage.S_StopStatus').','.       //status
        //        trans('sellManage.S_EventDetailSelectSeat').trans('sellManage.S_EventSeatTotal').','.   //指定席票數
        //        trans('sellManage.S_EventDetailFreeSeat').trans('sellManage.S_EventSeatTotal').','.   //自由席票數
        //        trans('sellManage.S_EventDetailSelectSeat').trans('sellManage.S_EventOnPorcessTotal').','.   //指定席予約済
        //        trans('sellManage.S_EventDetailFreeSeat').trans('sellManage.S_EventOnPorcessTotal').','.   //自由席予約済
        //        trans('sellManage.S_EventDetailSelectSeat').trans('sellManage.S_EventSellTotal').','.   //指定席已售出
        //        trans('sellManage.S_EventDetailFreeSeat').trans('sellManage.S_EventSellTotal').','.   //自由席已售出
        //        trans('sellManage.S_EventNoSellTotal').trans('sellManage.S_Ticketting').','. //保留位発券數
        //        trans('sellManage.S_EventNoSellTotal').trans('sellManage.S_EventSeatTotal').','. //保留位票數
        //        trans('sellManage.S_EventDetailSelectSeat').trans('sellManage.S_EventRestOfSeat').','.  //指定席尚餘票數
        //        trans('sellManage.S_EventDetailFreeSeat').trans('sellManage.S_EventRestOfSeat').','.  //自由席尚餘票數
        //        trans('sellManage.S_EventMaxTotal').        //銷售額
        //         '\n';

        $csv = trans('sellManage.S_EventOpenDate').' / '.trans('sellManage.S_EventOpenTime').','.  
                trans('sellManage.S_EventTimeSlot').','.    
                trans('sellManage.S_EventDetailSeatName').','.    
                trans('sellManage.S_EventSeatTotal').','.       
                trans('sellManage.S_EventOnPorcessTotal').','. 
                trans('sellManage.S_EventSellTotal').','.   
                trans('sellManage.S_EventNoSellTotal').','.  
                trans('sellManage.S_EventRestOfSeat').','.   
                trans('sellManage.S_EventMaxTotal').  
                '\n';
        //STS task 25 2020/06/24 end

        $seattotal = 0;
        $seatreservation = 0;
        $seatsold = 0;
        $seatres = 0;
        $seatremainingtotal = 0;
        $seattotalprice = 0;
        foreach($schedule_inf as $item){
            //STS task 25 2020/08/05 START FIX
              
             $seatData = $item['seat_Data'];
             $firstSeatData = $item['seat_Data_First'];
             //$subtotal = ($item['subtotal'] != null && $item['subtotal'] != '')? $item['subtotal'] : 0;
             // $seattotalprice += $subtotal;
             $seattotalprice += $firstSeatData['seat_price'];
             $seatreservation +=  $firstSeatData['seat_reservation'];
             $seatsold +=  $firstSeatData['seat_sold'];
             $seatres +=  $firstSeatData['seat_res'];
             // if(($firstSeatData['seat_total'] <= 0 || $firstSeatData['seat_total'] === '0' )&& $performance_inf['sch_kbn'] != 1){
             if(intval($firstSeatData['seat_total']) == 0 && $performance_inf['sch_kbn'] == 0){
                    $stock_limit = trans('common.S_Unlimited');
                    $unsell_free = trans('common.S_Unlimited');
                }else {
                    $stock_limit = $firstSeatData['seat_total'];
                    $seattotal += intval($firstSeatData['seat_total']);
                    $unsell_free = strval(intval($firstSeatData['seat_total'])-($firstSeatData['seat_reservation'])-( $firstSeatData['seat_sold']));
                    $seatremainingtotal += $unsell_free;

                }
           
             $csv .= $item['performance_date'].' '.$item['start_time'].','. //開催日 / 開催時間
                     $item['stage_name'].','. //活動日期
                     $firstSeatData['seat_name'].','.    //取消狀態
                     $stock_limit.','.
                     $firstSeatData['seat_reservation'].','.   //票數席位
                     $firstSeatData['seat_sold'].','.   //票數自由
                     $firstSeatData['seat_res'].','. //予約済席位
                     $unsell_free.','.    //予約済自由
                     //$subtotal.        //銷售額
                     $firstSeatData['seat_price'].       //STS 2021/08/03 Task 25
                     '\n';
           
     
            foreach($seatData as $seat){
        
                if(intval($seat['seat_total']) == 0 && $performance_inf['sch_kbn'] == 0){
                    $stock_limit = trans('common.S_Unlimited');
                    $unsell_free = trans('common.S_Unlimited');
                }else {
                    $stock_limit = $seat['seat_total'];
                    $unsell_free = strval(intval($seat['seat_total'])-($seat['seat_reservation'])-( $seat['seat_sold']));
                    $seattotal +=  intval($seat['seat_total']);
                    $seatremainingtotal += $unsell_free;
                }
                $seattotalprice += $seat['seat_price']; //STS 2021/08/06 Task 25 Fix
                $seatreservation +=  $seat['seat_reservation'];
                $seatsold +=  $seat['seat_sold'];
                $seatres +=  $seat['seat_res'];

                $csv .= ','.    
                    ','.  
                    $seat['seat_name'].','.   
                    $stock_limit.','.
                    $seat['seat_reservation'].','.   
                    $seat['seat_sold'].','.  
                    $seat['seat_res'].','. 
                    //$unsell_free.    
                    //STS 2021/08/03 Task 25
                    $unsell_free.','. 
                    $seat['seat_price'].   
                    '\n';
                    }
               
                    // $seatres +=  $firstSeatData['seat_res'];

                // $csv .= $item['performance_date'].','.    //活動日期
                //         $item['start_time'].','.    //場次時間
                //         $item['stage_name'].','.    //場次名稱
                //         $item['cancel_flg'].','.    //取消狀態
                //         $item['SALE'].','.   //票數席位
                //         $stock_limit.','.   //票數自由
                //         $item['cnt_inpay_rev'].','. //予約済席位
                //         $item['cnt_inpay_free'].','.    //予約済自由
                //         $item['cnt_sale_rev'].','.  //已售出席位
                //         $item['cnt_sale_free'].','. //已售出自由
                //         $item['cnt_rev_issue'].','. //保留席出票
                //         $item['RES'].','.   //保留位
                //         $unsell_seat.','.   //尚餘票數席位
                //         $unsell_free.','.    //尚餘票數自由
                //         $item['subtotal'].        //銷售額
                //         '\n';
        }
       
        if($seattotal == 0 && $performance_inf['sch_kbn'] != 1){
            $seatremainingtotal = trans('common.S_Unlimited');
            $seattotal = trans('common.S_Unlimited');
        }
        $csv .= ','.trans('sellManage.S_TableTotal').','.
        ','.   
        $seattotal.','.
        $seatreservation.','.   
        $seatsold.','.   
        $seatres.','. 
        $seatremainingtotal.','. 
        $seattotalprice.   
        '\n';

        //STS task 25 2020/06/24 end

        $result = array(
           'performance_inf' => $performance_inf,
           'schedule_inf' => $schedule_inf,
           'csv' => $csv,
        );
        
        return $result;
     } 
    /**
     * UserController constructor.
     * @param SellManageRepositories
     * @return $seatInf
     */
    public function getScheduleData($performanceId)
    {
        $GLID = session('GLID');
        $account_cd = session('account_cd');
        $performance_id = $performanceId;
        $data = [];
        $totalSeatReserve = 0;
        $totalAllSeat = 0;
        $totalSell = 0;
        $totalUnSell = 0;
        $totalSellPrice = 0;
        // $csv = '開催日,開催時間,時段名稱,狀態,販賣數,座席數,保留席,殘席數,上額\n';
        $csv = 
        trans('sellManage.S_EventOpenDate').','.
        trans('sellManage.S_EventOpenTime').','.
        trans('sellManage.S_EventTimeSlot').','.
        trans('sellManage.S_StopStatus').','.
        trans('sellManage.S_EventSeatTotal').','.
        trans('sellManage.S_EventSellTotal').','.
        trans('sellManage.S_EventNoSellTotal').','.
        trans('sellManage.S_EventRestOfSeat').','.
        trans('sellManage.S_EventMaxTotal').
        '\n';


        $performanceData = $this->SellManageRepositories->getPerformanceData($performance_id);
        $scheduleData = $this->SellManageRepositories->getScheduleData($performance_id);
        $seatTotalData = $this->SellManageRepositories->getSeatTotal($performance_id);
      
        session(
            [
             'performance_id' => $performanceId,
             'performance_status' => $performanceData['status'],
            ]
        );

        foreach($scheduleData as $inf){
            $seatTotal = 0;
            $seatReserve = 0;
            $seatSell = 0;
            $seatSellPrice = 0;

            //座位
            foreach($seatTotalData as $value){                 
                $seatSellData = $this->SellManageRepositories->getSeatSellData($performance_id, $value->alloc_seat_id, $inf->schedule_id);
                if(
                    $value->stage_seat_alloc_seat_id && 
                    !$value->stage_seat_seat_class_id &&
                    $value->stage_seat_schedule_id == $inf->schedule_id
                ){
                    
                }else{
                    $seatTotal++;
                }

                if($value->stage_seat_alloc_seat_id){
                    if($value->stage_seat_schedule_id === $inf->schedule_id){
                        if($value->stage_seat_reserve_code){
                            $seatReserve++;
                        }
                    }
                }else{   
                    if($value->reserve_code){
                        $seatReserve++;
                    }
                }
                
                foreach($seatSellData as $sellValue){

                    $seatSell++;
                    $seatSellPrice += $sellValue->seat_sale_seat_sale_price + $sellValue->seat_sale_commission_sv + $sellValue->seat_sale_commission_payment + $sellValue->seat_sale_commission_ticket + $sellValue->seat_sale_commission_delivery + $sellValue->seat_sale_commission_sub + $sellValue->seat_sale_commission_uc;
                 
                }
            }
            
            //自由
            $freeSeatToatl = $this->SellManageRepositories->getFreeSeatTotal($inf->schedule_id);
            if(isset($freeSeatToatl[0])){
                $seatTotal += $freeSeatToatl[0]['total'];
            }

            if($inf['stage_name']){
                $timeTitle = $inf['stage_name'];
            }else{
                $timeTitle = $inf['start_time'];
            }
          
            if($inf['cancel_flg'] == 1){
                $textCancel = trans('sellManage.S_Status_Stopped');
            }else{
                $textCancel = "";
            }
            
            // $csv .= $inf['performance_date'].','.$inf['start_time'].','.$timeTitle.','.$textCancel.','.$seatTotal.','.($seatSell+$seatReserve).','.$seatReserve.','.($seatTotal-($seatSell+$seatReserve)).','.$seatSellPrice.'\n';
            $csv .= $inf['performance_date'].','.$inf['start_time'].','.$timeTitle.','.$textCancel.','.$seatTotal.','.$seatSell.','.$seatReserve.','.($seatTotal-($seatSell+$seatReserve)).','.$seatSellPrice.'\n';

            $data[] = array(
                'scheduleId' => $inf->schedule_id,
                'openDate' => $inf['performance_date'],
                'openTime' => $inf['start_time'],
                'cancel_flg' => $inf['cancel_flg'],
                'timeTitle' => $timeTitle,
                'seatTotal' => $seatTotal,
                'seatReserve' => $seatReserve,
                // 'seatSell' => $seatSell+$seatReserve,
                'seatSell' => $seatSell,
                'unSell' => $seatTotal-($seatSell+$seatReserve),
                'seatSellPrice' => $seatSellPrice,
            );
            
            // $totalSell += $seatSell+$seatReserve;
            $totalSell += $seatSell;
            $totalUnSell += $seatTotal-($seatSell+$seatReserve);
            $totalSellPrice += $seatSellPrice;
            $totalAllSeat += $seatTotal;
            $totalSeatReserve += $seatReserve;

        }
        
        $seatInf = array(
            'performanceName'       => $performanceData->performance_name,
            'performanceNameSub'    => $performanceData->performance_name_sub,
            'totalSell'             => $totalSell,
            'totalUnSell'           => $totalUnSell, 
            'totalSellPrice'        => $totalSellPrice,
            'totalAllSeat'          => $totalAllSeat,
            'totalSeatReserve'      => $totalSeatReserve,
            'data'                  => $data,
            'csv'                   => $csv,
        );

        return $seatInf;
    } 
    /**
     * getStageData.
     * @param $performanceId
     * @return $stageList
     */
    public function insertDraw(array $request=null)
    {   
        $json           = json_decode($request['json'], true);
        $account_cd     = session('account_cd');
        $user_code      = session('user_code');
        $nowDateTime    = date("Y/m/d H:i:s");
        $result         = array(
                                'status'        => false,
                                'msn'           => '',
                                'schedule_id'   => '',
                            );
       
        $reserve_no     =  date("ym") . '-'. $user_code . '-R' . time();
        $ticketType     =  $json[0]['status'][0]['ticketType'];
        
        if(isset($json[0])){
            $orderExists           = null;
            $result['schedule_id'] = $json[0]['seatInf']['inf']['schedule_id'];
            
            if($ticketType == 'seat'){
                $cheackData = array(
                    'alloc_seat_id' => $json[0]['seatInf']['inf']['alloc_seat_id'],
                    'schedule_id'   => $json[0]['seatInf']['inf']['schedule_id'],
                );
                
                $orderExists = $this->SellManageRepositories->cheackSeatIsOrder($cheackData);
            }

            if(!$orderExists || $ticketType == 'freeSeat'){
                $pickup_method = $json[0]['inf'][0]['pickupMethod'];
                $pickup_method_num = 0;
                $pickup_no = null;
                //change pickup method
                switch ($pickup_method) {
                    case "mobapass":
                        $pickup_method_num = \Config::get('constant.pickup_method.eticket');
                        break;
                    case "qrpass":
                        $pickup_method_num = \Config::get('constant.pickup_method.qrpass_sms');
                        break;
                    case "ibon":
                        $pickup_method_num = \Config::get('constant.pickup_method.ibon');
                        $pickup_no =  str_pad (str_replace(".","",microtime(true)) , 14, 0 );
                        $pickup_no = substr($pickup_no,5);
                        break;
                    case "sevenEleven":
                        $pickup_method_num = \Config::get('constant.pickup_method.store');
                        break;
                    case "resuq":
                        $pickup_method_num = \Config::get('constant.pickup_method.resuq');
                        break;
                    default:
                        $pickup_method_num = 0;
                        break;
                }
                $sid = $this->MemberRepositories->getSIDbyGLID($json[0]['seatInf']['inf']['GLID']);

                $filterData = array(
                    'schedule_id'   => $result['schedule_id']
                );
                $scheduleInf = $this->SellManageRepositories->getScheduleInf($filterData);
                $genreralResrvationData = array(
                    'GLID'                 => $json[0]['seatInf']['inf']['GLID'],
                    'reserve_no'           => $reserve_no,
                    'receipt_kbn'          => 3,
                    'member_id'            => 'gettiis$'.$json[0]['inf'][0]['memberId'],
                    'consumer_name'        => $json[0]['inf'][0]['memberName'],
                    'reserve_date'         => $nowDateTime,
                    'pay_method'           => 0,
                    'tel_num'              => $json[0]['inf'][0]['phoneNum'],
                    'pickup_method'        => $pickup_method_num,
                    'cs_pickup_no'         => $pickup_no,
                    'mail_address'         => $json[0]['inf'][0]['mail'],
                    'pickup_st_date'       => $nowDateTime,
                    'pickup_due_date'      => $scheduleInf['performance_date'] .' ' . $scheduleInf['start_time'],
                    'receive_account_cd'   => $account_cd,
                    'commission_sv'        => 0,
                    'commission_payment'   => 0,
                    'commission_ticket'    => 0,
                    'commission_delivery'  => 0,
                    'commission_sub'       => 0,
                    'commission_uc'        => 0,
                    'update_account_cd'    => $account_cd,
                    'SID'                  => $sid,
                );
                
                $order_id = $this->SellManageRepositories->insertReserve($genreralResrvationData);
                // $order_id = 1;
                               
                if($order_id){
                    if($ticketType == 'seat'){
                        $alloc_seat_id   = $json[0]['seatInf']['inf']['alloc_seat_id'];
                        $schedule_id     = $json[0]['seatInf']['inf']['schedule_id'];
                        $seat_class_id   = null;
                        $seat_class_name = $json[0]['seatInf']['seatTitle'];
                        $seat_seq        = 0;
                        $reserve_code    = $this->SellManageRepositories->getSeatAllocReserveID($alloc_seat_id,$schedule_id);
                    }else{
                        $alloc_seat_id   = null;
                        $schedule_id     = $json[0]['seatInf']['inf']['schedule_id'];
                        $seat_class_id   = $json[0]['seatInf']['inf']['seat_class_id'];
                        $seat_class_name = $json[0]['seatInf']['inf']['seatTitle'];
                        $seat_seq        = $this->SellManageRepositories->getFreeSeatNum($seat_class_id,$schedule_id);
                        $reserve_code    = null;
                    }
    
                    $seatSaleData = array(
                        'alloc_seat_id'         => $alloc_seat_id,
                        'schedule_id'           => $schedule_id,
                        'sale_type'             => 1,
                        'seat_class_id'         => $seat_class_id,
                        'reserve_code'          => $reserve_code,
                        'ticket_class_id'       => null,
                        'order_id'              => $order_id,
                        'reserve_seq'           => 1,
                        'seat_seq'              => $seat_seq,   
                        'seat_class_name'       => $seat_class_name,
                        'seat_status'           => 3,
                        'reserve_period_code'   => 0,
                        'commission_sv'         => 0,
                        'commission_payment'    => 0,
                        'commission_ticket'     => 0,
                        'commission_delivery'   => 0,
                        'commission_sub'        => 0,
                        'commission_uc'         => 0,
                        'update_account_cd'     => $account_cd,
                        'SID'                   => $sid,
                        'member_id'             => $json[0]['inf'][0]['memberId'],
                    );
                }else{
                    $result['status'] = false;
                    $result['msn']    = trans('sellManage.S_FailureUdate');
                    
                    return $result;
                }
                $seatSaleResult = $this->SellManageRepositories->insertSeatSale($seatSaleData);
              
                if(!$seatSaleResult){
                    $result['status'] = false;
                    $result['msn']    = trans('sellManage.S_FailureUdate');
                }else{
                    
                    /* 
                    // 0706 James Lai : Disable mail notification, and mailing from front-end
                    if($pickup_method === \Config::get('constant.pickup_method.ibon') || $pickup_method === \Config::get('constant.pickup_method.store')){
                        $resendResult = $this->SendMail->sendDrawMail($json[0]);

                        if(!$resendResult){
                            $result['msn']    = trans('sellManage.S_FailureMailing');
                        }
                    }
                    */
                    switch ($pickup_method) {
                        case "sevenEleven":
                            // Call lspaymentModule API to getting pickup number
                            $pickup_no = LSPayment\Api::getSEJPickupNum($order_id);
                            //[TODO] return error handle
                            if(!$pickup_no) {
                                $this->SellManageRepositories->setSeatAllocFail();
                                $result['status'] = false;
                                //$result['msn']    = trans('Send SEJ ticket error');
                                $result['msn']    = 'セブン発券処理に失敗しました。';
                                return $result;    
                            }
                            break;
                    }
    
                    //與 gettis 同步
                    if($ticketType == 'seat'){
                        $filterData = array(
                            'alloc_seat_id' => $json[0]['seatInf']['inf']['alloc_seat_id'],
                        );
                    
                        $seatInfo = $this->SellManageRepositories->getSeatInfo($filterData);

                    }else{
                        //0706 James : ??
                        $seatInfo[] = (object)[
                            'serial'            => 0, 
                            'type'              => 0,
                            'sid'               => 0,
                            'seq'               => 0,
                            'disp'              => true,
                            'tkcd'              => 0,
                        ];
                    }
                    $filter_data = array(
                        'stcd' => $scheduleInf['stage_code'],
                    );

                    $stag_code =  $this->SellManageRepositories->getStagNameInfo($filter_data);
                    $purchase_id = $this->getPurchase_id($scheduleInf['distributor_code'],$reserve_no);

                    $genreralResrvationData = array(
                        'purchase_id'             => $purchase_id,
                        'reservation_no'          => $reserve_no,
                        'receipt_no'              => $reserve_no,
                        'payment_slip_no'         => '',
                        'exchange_slip_no'        => $pickup_no?$pickup_no:'',
                        'user_id'                 => $json[0]['inf'][0]['memberId'],
                        'distributor_code'        => $scheduleInf['distributor_code'],
                        'performance_code'        => $scheduleInf['performance_code'],
                        'stage_code'              => $stag_code['stage_num'],
                        'performance_date'        => $scheduleInf['performance_date'],
                        'reservation_date'        => $nowDateTime,
                        'seat_type'               => $seat_class_id * -1,
                        'seat_name'               => ($ticketType == 'seat')?$json[0]['seatInf']['seatTitle']:$json[0]['seatInf']['inf']['seatTitle'],
                        'ticket_price'            => 0,
                        'ticket_count'            => 1,
                        'price'                   => 0,
                        'payment_type'            => 0,
                        'collect_type'            => $pickup_method_num,
                    );

                    $status = array();
            
                    $data = array(
                        'saleData'  => $genreralResrvationData,
                        'seatInfo'  => $seatInfo,
                    );
                    
                    $json = array(
                        'status'    =>  $status,
                        'data'      =>  $data,
                    );

                    
                    $json =  json_encode($json);

                    $post_data = array(
                        'performance_code'  => $scheduleInf['performance_code'],          
                        'reserve_no'        => $reserve_no,
                        'json'              => $json,
                    );

                    if($this->PostApiServices->post($post_data)){
                        $result['status'] = true;
                        $result['msn']    = trans('sellManage.S_SucceedUdate');     
                    }
                    else {
                        $this->SellManageRepositories->setSeatAllocFail();
                        $result['status'] = false;
                        $result['msn']    = trans('sellManage.S_FailureUdate');
                    }
                    
                }
                return $result;
            }else{
                $result['status'] = false;
                $result['msn']    = trans('sellManage.S_OrderDuplicated');

                return $result;
            }
        }
    }
    /**
     * send notice email
     * @param
     * @return
     */
    public function sendDrawNoticeMail(array $request=null){
        $json = json_decode($request['json'], true);
        $result         = array(
            'status'        => false,
            'schedule_id'   => '',
        );
        $result['schedule_id'] = $json[0]['seatInf']['inf']['schedule_id'];

        $result['status'] = $this->SendMail->sendDrawMail($json[0]);

        return $result;
    }
    /**
    * get performance data for index list show
    * 
    */
    public function performanceListForReport(array $request)
    {
      $date = explode("-", $request['date']); 
      $filterData = array(
          'GLID'      => $request['GLID'],
          'startdt'   =>  date("Y/m/d" ,strtotime(trim($date[0]))),
          'enddt'     =>  date("Y/m/d" ,strtotime(trim($date[1]))),
      );
      $preformanceData = $this->SellManageRepositories->performanceListForReport($filterData); 
      
      foreach ($preformanceData as $preformance) 
      {
         $dispStatus = $this->getPerformanceDispStatusStr($preformance['status']);
         $preformance['status'] = $dispStatus;
      }
      return $preformanceData;
    } 
    /**
     * getSummaryDataForSystemReport.
     * @param $id,$performanceId,$dateFrom,$dateTo
     * @return $summaryList
     * システム精算明細書-合計内訳算出
     */
     public function getSummaryDataForSystemReport($GLID, $id,$performanceId,$dateFrom,$dateTo)
     {
         //システム精算明細書 
        //システム利用料
        $RunningCommission = $this->SellManageRepositories->getCommissionRateAmount($GLID,\Config::get('constant.client_commission.system'),$dateFrom,$dateTo);
        //クレジット決済手数料
        $CreditCommission = $this->SellManageRepositories->getCommissionRateAmount($GLID,\Config::get('constant.client_commission.card_payment'),$dateFrom,$dateTo);
        //クレジット売上取消手数料
        $CreditCancelCommission = $this->SellManageRepositories->getCommissionRateAmount($GLID,\Config::get('constant.client_commission.cancel'),$dateFrom,$dateTo);
        //セブン発券手数料
        $SevenPickup = $this->SellManageRepositories->getCommissionRateAmount($GLID,\Config::get('constant.client_commission.seven_pickup'),$dateFrom,$dateTo);

        //クレジットカード受領代金-チケット代金（件数、枚数、金額）、セブン代理受領代金-チケット代金（件数、枚数、金額）、セブンイレブン手数料-発券手数料、代理受領手数料
        $this->SellManageRepositories->getTicketSoldPrice($GLID,$id,$performanceId,$dateFrom,$dateTo,\Config::get('constant.pay_method.card'));   
        //クレジットカード受領代金-発券手数料、サービス利用料
        $this->SellManageRepositories->getTicketSoldCommission($id,$performanceId,$dateFrom,$dateTo,\Config::get('constant.pay_method.card'));
        //クレジットカード受領代金-キャンセル代金（件数、枚数、金額）、クレジットカード手数料等-キャンセル手数料
        $this->SellManageRepositories->getTicketSoldCancel($GLID, $id,$performanceId,$dateFrom,$dateTo,\Config::get('constant.pay_method.card'));
        //セブン代理受領代金-チケット代金（件数、枚数、金額）
        $this->SellManageRepositories->getTicketSoldPrice($GLID,$id,$performanceId,$dateFrom,$dateTo,\Config::get('constant.pay_method.store'));
        //セブン代理受領代金-発券手数料、支払手数料、サービス料
        $this->SellManageRepositories->getTicketSoldCommission($id,$performanceId,$dateFrom,$dateTo,\Config::get('constant.pay_method.store'));
        //クレジットカード手数料等-決済手数料
        $this->SellManageRepositories->getCreditCardPaymentCommission($id,$performanceId,$dateFrom,$dateTo);
        //ランニング-システム利用料（一般）
        $this->SellManageRepositories->getRunningCommission($id,$performanceId,$dateFrom,$dateTo);    

        //システム精算明細書　サマリー
        $summaryinfo = $this->SellManageRepositories->getSysReportSummaryInfo($id); 

        $cardSoldPrice       = array(); //クレジットカード受領代金-チケット代金
        $cardSoldCommission  = array(); //クレジットカード受領代金-発券手数料サービス手数料
        $cardCancel          = array(); //クレジットカード受領代金-キャンセル代金

        $storeSoldPrice      = array(); //セブン代理受領代金-発券手数料 
        $storeSoldCommission = array(); //セブン代理受領代金-支払手数料 サービス手数料

        $cardPaymentCommission = array(); //クレジットカード手数料等-決済手数料、キャンセル手数料
        $storeCommission       = array(); //セブンイレブン手数料等-発券手数料、代理受領手数料
        $runingCommission      = array(); //ランニング
        $runingCommissionNum   = array(); //ランニング
        
        self::initializingArray($cardSoldPrice,$cardSoldCommission,$cardCancel,$storeSoldPrice,$storeSoldCommission,$cardPaymentCommission,$storeCommission,$runingCommission,$runingCommissionNum);
        
        foreach($summaryinfo as $summaryPrice)
        {
          self::setReportValue($summaryPrice,$cardSoldPrice,$cardSoldCommission,$cardCancel,$storeSoldPrice,$storeSoldCommission,$cardPaymentCommission,$storeCommission,$runingCommission,$runingCommissionNum);
        }
        //レコード有りの場合、初期値削除
        if(count($cardPaymentCommission['cardCancelCommission']) > 1)unset($cardPaymentCommission['cardCancelCommission'][0]);
        if(count($storeCommission['ticketCommission']) > 1)unset($storeCommission['ticketCommission'][0]);
        if(count($storeCommission['rceiptCommission']) > 1)unset($storeCommission['rceiptCommission'][0]);

        //システム精算明細書 1ページ目 summary info
        $sysRepoPerformanceInfo = array(
                                         "cardSoldPrice"         => $cardSoldPrice,
                                         "cardSoldCommission"    => $cardSoldCommission,
                                         "cardCancel"            => $cardCancel,
                                         "storeSoldPrice"        => $storeSoldPrice,
                                         "storeSoldCommission"   => $storeSoldCommission,
                                         "cardPaymentCommission" => $cardPaymentCommission,     
                                         'storeCommission'       => $storeCommission,
                                         'runingCommission'      => $runingCommission,
                                         'runingCommissionNum'   => $runingCommissionNum
                                       );
        
        //システム精算明細書 2ページ目以降
        //公演情報
        $perfprmance =  $this->SellManageRepositories->performanceDetailForReport($performanceId);
        
        //システム精算明細書　公演ごと
        $eventSummary = $this->SellManageRepositories->getSysReportInfo($id); 

        foreach( $perfprmance as $event)
        {          
          self::initializingArray($cardSoldPrice,$cardSoldCommission,$cardCancel,$storeSoldPrice,$storeSoldCommission,$cardPaymentCommission,$storeCommission,$runingCommission,$runingCommissionNum);
   
          foreach( $eventSummary as  $eventSummaryInfo)
          {   
            if($event->performance_id != $eventSummaryInfo->performance_id) continue;
            self::setReportValue($eventSummaryInfo,$cardSoldPrice,$cardSoldCommission,$cardCancel,$storeSoldPrice,$storeSoldCommission,$cardPaymentCommission,$storeCommission,$runingCommission,$runingCommissionNum);
          }
          //レコード有りの場合、初期値削除
          if(count($cardPaymentCommission['cardCancelCommission']) > 1)unset($cardPaymentCommission['cardCancelCommission'][0]);
          if(count($storeCommission['ticketCommission']) > 1)unset($storeCommission['ticketCommission'][0]);
          if(count($storeCommission['rceiptCommission']) > 1)unset($storeCommission['rceiptCommission'][0]);

          //システム精算明細書 公演毎 summary info
           $eventSysRepoPerformanceInfo[] = array(
                                                   "performance_code"   => $event->performance_code,
                                                   "performance_name"   => $event->performance_name,
                                                   "hall_disp_name"     => $event->hall_disp_name,
                                                   "performance_st_dt"  => $event->performance_st_dt,
                                                   "day_st"             => $event->day_st,
                                                   "performance_end_dt" => $event->performance_end_dt,
                                                   "day_end"            => $event->day_end,
                                                   "detail"             => array(
                                                                                  "cardSoldPrice"         => $cardSoldPrice,
                                                                                  "cardSoldCommission"    => $cardSoldCommission,
                                                                                  "cardCancel"            => $cardCancel,
                                                                                  "storeSoldPrice"        => $storeSoldPrice,
                                                                                  "storeSoldCommission"   => $storeSoldCommission,
                                                                                  "cardPaymentCommission" => $cardPaymentCommission,
                                                                                  'storeCommission'       => $storeCommission,
                                                                                  'runingCommission'      => $runingCommission,
                                                                                  'runingCommissionNum'  => $runingCommissionNum
                                                                                 )
                                                 );
        }
        $eventSysRepoPerformanceInfo = array("performance_detail" => $eventSysRepoPerformanceInfo);

        $sysRepoPerformanceInfo = array_merge($sysRepoPerformanceInfo,$eventSysRepoPerformanceInfo);
        $sysRepoPerformanceInfo = array_merge($sysRepoPerformanceInfo,array("RunningCommission" => $RunningCommission[0]));
        $sysRepoPerformanceInfo = array_merge($sysRepoPerformanceInfo,array("CreditCommission" => $CreditCommission[0]));
        $sysRepoPerformanceInfo = array_merge($sysRepoPerformanceInfo,array("CreditCancelCommission" => $CreditCancelCommission[0]));
        $sysRepoPerformanceInfo = array_merge($sysRepoPerformanceInfo,array("SevenPickup" => $SevenPickup[0]));
        $sysRepoPerformanceInfo = array_merge($sysRepoPerformanceInfo,array("fee" =>880));

        //sysreportテーブル削除
        $this->SellManageRepositories->deleteSysReportInfo($id);
        return $sysRepoPerformanceInfo;
     }
     private function initializingArray(&$cardSoldPrice,&$cardSoldCommission,&$cardCancel,&$storeSoldPrice,&$storeSoldCommission,&$cardPaymentCommission,&$storeCommission,&$runingCommission,&$runingCommissionNum)
     {
        //クレジットカード受領代金-チケット代金
        $cardSoldPrice= array(
                              "reserve_num" => 0,
                              "seats_num"   => 0,
                              "sale_price"  => 0
                             );
        //クレジットカード受領代金-発券手数料 
        $cardSoldCommission['commission_ticket'] = 0;
        //クレジットカード受領代金-サービス手数料
        $cardSoldCommission['commission_sv'] = 0;
        //クレジットカード受領代金-キャンセル代金
        $cardCancel = array(
                             'cancel_num'        => 0,
                             'cancel_sheets_num' => 0,
                             'refund_payment'    => 0
                           );               
        //セブン代理受領代金-チケット代金
        $storeSoldPrice= array(
                                "reserve_num" => 0,
                                "seats_num"   => 0,
                                "sale_price"  => 0
                              );    
        //セブン代理受領代金-発券手数料 
        $storeSoldCommission['commission_ticket'] = 0;
        //セブン代理受領代金-支払手数料 
        $storeSoldCommission['commission_payment'] = 0;
        //セブン代理受領代金-サービス手数料
        $storeSoldCommission['commission_sv'] = 0;      
        //クレジットカード手数料等
        //決済手数料
        $cardPaymentCommission['commission_card_payment'] = 0;
        //キャンセル手数料等
        $cardPaymentCommission['cardCancelCommission'] = array();
        $cardPaymentCommission['cardCancelCommission'][0] = array(
                                                               "unit_price" => 0,
                                                               "unit_rate" => 0,
                                                               "cancel_num" => 0,
                                                               "apply_date" => "",
                                                               "cancel_commission"  => 0
                                                              );
        //セブンイレブン手数料等
        //発券手数料
        $storeCommission['ticketCommission'] = array();
        $storeCommission['ticketCommission'][0] = array(
                                                        "unit_price" => 0,
                                                        "seats_num" => 0,
                                                        "apply_date" => "",
                                                        "ticket_commission"  => 0
                                                       );
        //代理受領手数料
        $storeCommission['rceiptCommission'] = array();
        $storeCommission['rceiptCommission'][0] = array(
                                                        "unit_price" => 0,
                                                        "reserve_num" => 0,
                                                        "apply_date" => "",
                                                        "receipt_commission"  => 0
                                                        );
        //ランニング
        $runingCommissionNum = 0;
        $runingCommission = 0;
        
     }                                
     private function setReportValue($eventSummaryInfo,&$cardSoldPrice,&$cardSoldCommission,&$cardCancel,&$storeSoldPrice,&$storeSoldCommission,&$cardPaymentCommission, &$storeCommission, &$runingCommission,&$runingCommissionNum)
     {
       if($eventSummaryInfo->commission_type == \Config::get('constant.sysrep_comtype.card_receipt'))
       {
         //クレジットカード受領代金
         if($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.ticket'))
         {
           //クレジットカード受領代金-チケット代金
           $cardSoldPrice= array(
                                  "reserve_num" => $eventSummaryInfo->number,
                                  "seats_num"   => $eventSummaryInfo->sheets_number,
                                  "sale_price"  => $eventSummaryInfo->amount
                                 );
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.pickup')){
           //クレジットカード受領代金-発券手数料 
           $cardSoldCommission['commission_ticket'] = (\App::getLocale() == "ja" )? 0 : $eventSummaryInfo->amount; //modified by LS#1475 日本版不要項目
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.service')){
           //クレジットカード受領代金-サービス手数料
           $cardSoldCommission['commission_sv'] =  (\App::getLocale() == "ja" )? 0 : $eventSummaryInfo->amount; //modified by LS#1475 日本版不要項目
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.cancel_fee')){
           //クレジットカード受領代金-キャンセル代金
           $cardCancel = array(
                                'cancel_num'        => $eventSummaryInfo->number,
                                'cancel_sheets_num' => $eventSummaryInfo->sheets_number,
                                'refund_payment'    => $eventSummaryInfo->amount
                               );
         }
       }elseif($eventSummaryInfo->commission_type == \Config::get('constant.sysrep_comtype.seven_receipt')){
         //セブン代理受領代金    
         if($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.ticket'))
         {
           //セブン代理受領代金-チケット代金
           $storeSoldPrice= array(
                                  "reserve_num" => $eventSummaryInfo->number,
                                  "seats_num"   => $eventSummaryInfo->sheets_number,
                                  "sale_price"  => $eventSummaryInfo->amount
                                 );    
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.pickup')){
           //セブン代理受領代金-発券手数料 
           $storeSoldCommission['commission_ticket'] = (\App::getLocale() == "ja" )? 0 : $eventSummaryInfo->amount; //modified by LS#1475 日本版不要項目
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.service')){
           //セブン代理受領代金-支払手数料 
           $storeSoldCommission['commission_sv'] = (\App::getLocale() == "ja" )? 0 : $eventSummaryInfo->amount; //modified by LS#1475 日本版不要項目
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.amount')){
           //セブン代理受領代金-サービス手数料
           $storeSoldCommission['commission_payment'] = (\App::getLocale() == "ja" )? 0 : $eventSummaryInfo->amount; //modified by LS#1475 日本版不要項目
         }
       }elseif($eventSummaryInfo->commission_type == \Config::get('constant.sysrep_comtype.card_commission')){
         //クレジットカード手数料等
         if($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.settlement')){
           //決済手数料
           $cardPaymentCommission['commission_card_payment'] = $eventSummaryInfo->amount;
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.cancel_amount')){
           //キャンセル手数料
           $cardPaymentCommission['cardCancelCommission'][] = array(
                                                                     "unit_price"        => $eventSummaryInfo->unit_price,
                                                                     "unit_rate"        => $eventSummaryInfo->unit_rate,
                                                                     "cancel_num"        => $eventSummaryInfo->number,
                                                                     "apply_date"      => $eventSummaryInfo->apply_date,
                                                                     "cancel_commission" => $eventSummaryInfo->amount
                                                                   );
         }
       }elseif($eventSummaryInfo->commission_type == \Config::get('constant.sysrep_comtype.seven_commission')){
         //セブンイレブン手数料等
         if($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.pickup')){
           //発券手数料
           $storeCommission['ticketCommission'][] = array(
                                                           "unit_price"         => $eventSummaryInfo->unit_price,
                                                           "seats_num"          => $eventSummaryInfo->sheets_number,
                                                           "apply_date"       => $eventSummaryInfo->apply_date,
                                                           "ticket_commission"  => $eventSummaryInfo->amount
                                                         );
         }elseif($eventSummaryInfo->payment_type == \Config::get('constant.sysrep_paytype.receipt')){             
           //代理受領手数料
           $storeCommission['rceiptCommission'][] = array(
                                                           "unit_price"          => $eventSummaryInfo->unit_price,
                                                           "reserve_num"         => $eventSummaryInfo->number,
                                                           "apply_date"        => $eventSummaryInfo->apply_date,
                                                           "receipt_commission"  => (\App::getLocale() == "ja" )? 0 : $eventSummaryInfo->amount //modified by LS#1475 日本版不要項目
                                                         );
         }     
       }elseif($eventSummaryInfo->commission_type == \Config::get('constant.sysrep_comtype.running')){
         //ランニング
         $runingCommissionNum = $eventSummaryInfo->sheets_number;
         $runingCommission = $eventSummaryInfo->amount;
       }           
     }

     private function getPurchase_id($userCD,$reserve_no) {
        $key = 'RESERVE2@test_key';
        $value = sprintf('%s:%s:%s','2',$userCD,$reserve_no);
        $signature = hash_hmac('sha1',$value,$key);

        $ret = sprintf('%s:%s',$value,$signature);
        
        return $ret;
     }
   /**
     * 取得活動列表
     * @return collections $orders_paginate
     */
    public function getPerformanceList(){
        $performanceList = '';

        try{
            Log::info('get performance list');

            if(session('root_account')){
                $GLID = null;
            }else{
                $GLID = session('GLID');
            }
            
            $performanceList = $this->SellManageRepositories->getPerformanceList($GLID);
        }catch(Exception $e){
            Log::error('function getPerformanceList :'.$e->getMessage());
            $performanceList = false;
        }finally{ 
            return $performanceList;
        }
    }
    /**
     * 訂單查詢 - 訂單查詢
     * @param string $filter_json
     * @return collections $orders_paginate
     */
    public function getAllOrders($filter_json){
        $result = '';

        try{
            Log::info('oders search');
            
            if(session('root_account')){
                $GLID = null;
            }else{
                $GLID = session('GLID');
            }
            
            $filter_inf = $this->getOrderFilterInf($filter_json);
            $result = $this->SellManageRepositories->getAllOrders($filter_inf, $GLID);
        }catch(Exception $e){
            Log::error('function getOrders :'.$e->getMessage());
            $result = false;
        }finally{ 
            return $result;
        }
    }
    /**
     * 訂單查詢
     * @param string $filter_json
     * @return collections $orders_paginate
     */
    public function getOrders($filter_json){
        $orders_paginate = '';

        try{
            Log::info('oders search');

            if(session('root_account')){
                $GLID = null;
            }else{
                $GLID = session('GLID');
            }

            $filter_inf = $this->getOrderFilterInf($filter_json);
            $orders_paginate = $this->SellManageRepositories->getOrders($filter_inf, $GLID);
        }catch(Exception $e){
            Log::error('function getOrders :'.$e->getMessage());
            $orders_paginate = false;
        }finally{ 
            return $orders_paginate;
        }
    }
    /**
     * 訂單資料
     * @param string $filter_json
     * @return collections $orders_paginate
     */
    public function getOrder($filter_json){
        $orders_paginate = '';

        try{
            Log::info('oders search');
            $filter_inf = $this->getOrderFilterInf($filter_json);
            $orders_paginate = $this->SellManageRepositories->getOrders($filter_inf);
        }catch(Exception $e){
            Log::error('function getOrders :'.$e->getMessage());
            $orders_paginate = false;
        }finally{ 
            return $orders_paginate;
        }
    }
    
    /**
     * 訂單資料整理成 csv 格式 
     * 
     * @param array $reservation_data
     * @return array $result
     */
    public function detailCsv($reservation_data, $sch_kbn, $questionnaires){
        $result = array();

        $csv_title = array(
            trans('sellManage.S_EventDetailTable'),
            trans('sellManage.S_EventDetailTableDate'),
            trans('sellManage.S_EventDetailTableId'),
            trans('sellManage.S_EventDetailTableName'),
            trans('sellManage.S_EventDetailCSVTel'), // STS 2021/08/11 task 45
            trans('sellManage.S_EventDetailCSVMail'), // STS 2021/08/11 task 45
            trans('sellManage.S_EventDetailTablePay'),
            trans('sellManage.S_EventDetailTableGet'),
            trans('sellManage.S_EventDetailTableBill'),
            trans('sellManage.S_EventDetailTableTicketing'),
            trans('sellManage.S_EventDetailTableVisit'),
            trans('sellManage.S_EventDetailTableVisitDate'),
            trans('sellManage.S_EventDetailTableVisitGate'), //STS 2021/09/01 Task 49
            trans('sellManage.S_EventDetailTableComplete'),
            trans('sellManage.S_EventDetailTableNum'),
            trans('sellManage.S_EventDetailTableTPSum'),
            trans('sellManage.S_EventDetailTableTotal'),
            trans('sellManage.S_Orderidno'),
            trans('sellManage.S_Seatidno'),
            trans('sellManage.S_EventDetailSeatName'),
            trans('sellManage.S_EventDetailTicketName'),
            trans('sellManage.S_EventDetailSeatType'),
            trans('sellManage.S_EventDetailSeatPosition'),
            trans('sellManage.S_EventDetailReferencenNumber'),
            trans('sellManage.S_EventDetailTableTP'),
            trans('sellManage.S_ReserveCancel'),
             //STS 2021/08/11 task 45 START
            trans('sellManage.S_EventDetailTel'),
            trans('sellManage.S_EventDetailEmail'),
            trans('sellManage.S_EventDetailAllowEmail'),
             //STS 2021/08/11 task 45 START
        );

        //フリーアンケート 2021/04/09 LS-Itabashi
        foreach($questionnaires as $questionnaire) {
            $csv_title[] = trans('sellManage.S_FreeQuestionDesc');
            $csv_title[] = trans('sellManage.S_FreeQuestionAns');
        }

        array_push($result, $csv_title);
    
        foreach($reservation_data as $order){
            $reserve_no = ($order['reserve_no'])?$order['reserve_no']:'-';
            $reserve_date = ($order['reserve_date'])?$order['reserve_date']:'-';
            $member_id = ($order['member_id'])?$order['member_id']:'-';
            $consumer_name = ($order['consumer_name'])?$order['consumer_name']:'-';
            $tel_num = ($order['tel_num'])?$order['tel_num']:'-';
            $mail_address = ($order['mail_address'])?$order['mail_address']:'-';
            $pay_method = '';
            $pickup_method = '';
            $payment_flg = '';
            $issue_flg = '';
            $refund = '';
            $total_pie = ($order['total_pie'])?$order['total_pie']:'-';
            $total_price = ($order['total_price'])?$order['total_price']:'-';
            $all_cost = ($order['allCost'])?$order['allCost']:'-';
            $order_cancel_reason = ($order['order_cancel_reason'])?$order['order_cancel_reason']:'-';
            // STS 2021/08/13 task 45 -- START
            if(!empty($order['tel'])) {
                $tel = $order['tel'];
            } else $tel = '-';

             if(!empty($order['email'])) {
                $email = $order['email'];
                if(isset($order['allow_email'])) {
                  if($order['allow_email'] === 1) {
                  $allow_email  = trans('sellManage.S_AllowMail');
                } else if($order['allow_email'] === 0) {
                     $allow_email = trans('sellManage.S_DisallowMail');
                } else {
                     $allow_email = '-';
                }
              

            } else {
             $allow_email = '-';
            }
            } else {
                $email = '-';
                $allow_email = '-';
            } 

            /// STS 2021/08/13 task 45 --END


            //修改訂單金額
            if($order['total_price'] !== '-'){
                if($order['revise_amount']['reviseStatus'] && $order['revise_amount']['data']['status'] ){
                    $all_cost = $order['revise_amount']['data']['amount_total'];
                }
            }

            //付款方式
            switch($order['pay_method']){
                case -1:
                    $pay_method = 'X';
                    break;
                case 1:
                    $pay_method = trans('sellManage.S_EventDetailCash');
                    break;
                case 2:
                    $pay_method = trans('sellManage.S_EventDetailCreditCard');
                    break;
                case 3:
                    $pay_method = trans('sellManage.S_EventDetailConvenience');
                    break;
                case 31:
                    $pay_method = 'ibon';
                    break;
                case 20:
                    $pay_method = trans('sellManage.S_EventDetailFree');
                    break;
                case 4:
                    $pay_method = '-';
                    break;
                default:
                    $pay_method = '?';
                    break;
            }

            //取票方式
            switch($order['pickup_method']){
                case -1:
                    $pickup_method = 'X';
                    break;
                case 3:
                    $pickup_method = trans('sellManage.S_EventDetailConvenience');
                    break;
                case 4:
                    $pickup_method = '-';
                    break;
                case 8:
                    $pickup_method = 'れすQ';
                    break;
                case 11:
                    $pickup_method = '発券なし';
                    break;
                case 9:
                    $pickup_method = trans('sellManage.S_EventDetailPickup_ET');
                    break;
                case 31:
                    $pickup_method = 'ibon';
                    break;
                case 91:
                    $pickup_method = 'QRPASS';
                    break;
                case \Config::get('constant.pickup_method.no_ticketing'):
                    $pickup_method =  trans('sellManage.S_Pickup_NoTicketing');
                    break;
                default:
                    $pickup_method = '?';
                    break;
            }

            //是否取票
            switch($order['issue_flg']){
                case 0:
                    $issue_flg = 'X';
                    break;
                case 1:
                    $issue_flg = '○';
                    break;
                case 2:
                    $issue_flg = '△';
                    break;
                default:
                    $issue_flg = '-';
                    break;
            }

            //是否付款
            switch($order['payment_flg']){
                case 0:
                    $payment_flg = 'X';
                    break;
                case 1:
                    $payment_flg = '○';
                    break;
                case 2:
                    $payment_flg = '△';
                    break;
                default:
                    $payment_flg = '-';
                    break;
            }

            $refund = '-';

            foreach($order['seatData'] as $key => $seat){
                $seat_type = '';
                $visit_flg = $seat['visit_flg'];
                $visit_date = $seat['visit_date'];
                $visit_gate = $seat['visit_gate']; //STS 2021/09/01 Task 49
                $seat_no = $key + 1;
                $seat_seq = $seat['seat_seq'];
                $seat_title = ($seat['seatTitle'])?$seat['seatTitle']:'-';
                $ticket_title = ($seat['ticketTitle'])?$seat['ticketTitle']:'-';
                $seat_type = '';
                $seat_position = ($seat['seatPosition'])?$seat['seatPosition']:'-';
                $reference_number = '';
                $price = ($seat['price'])?$seat['price']:'-';
                
                switch($seat['seatType']){
                    case 1:
                        $seat_type = '指定席';
                        break;
                    case 3:
                        $seat_type = '押え席';
                        break;
                    case 'R':
                        $seat_type = '自由席';
                        if($sch_kbn == 1){
                            $reference_number = $seat['seat_seq'];
                        }
                        break;
                    default:
                        $seat_type = '-';
                        break;
                }
             
                //保留席-資料
                if($seat['seatType'] == 3){
                    $order_inf = $seat['orderInf'];
                    $reserve_no = ($order_inf['reserve_no'])?$order_inf['reserve_no']:'-';
                    $reserve_date = ($seat['reserve_date'])?$seat['reserve_date']:'-';
                    $member_id = ($seat['memberId'])?$seat['memberId']:'-';
                    $consumerName = ($seat['consumerName'])?$seat['consumerName']:'-';
                    $tel_num = ($order_inf['tel_num'])?$order_inf['tel_num']:'-';
                    $mail_address = ($order_inf['mail_address'])?$order_inf['mail_address']:'-';
                    $total_pie = 1;
                    $reserve_seq = ($order_inf['reserve_seq'])?$order_inf['reserve_seq']:'-';
                    
                    //取票方式
                    if(empty($order_inf['pickup_method'])) {
                        $pickup_method = '-';
                    }
                    else {
                        switch($order_inf['pickup_method']){
                            case -1:
                                $pickup_method = 'X';
                                break;
                            case 3:
                                $pickup_method = trans('sellManage.S_EventDetailConvenience');
                                break;
                            case 4:
                                $pickup_method = '-';
                                break;
                            case 8:
                                $pickup_method = 'れすQ';
                                break;
                            case 11:
                                $pickup_method = '発券なし';
                                break;
                            case 9:
                                $pickup_method = trans('sellManage.S_EventDetailPickup_ET');
                                break;
                            case 31:
                                $pickup_method = 'ibon';
                                break;
                            case 91:
                                $pickup_method = 'QRPASS';
                                break;
                            default:
                                $pickup_method = '?';
                                break;
                        }
                    }

                    //是否取票
                    switch($order_inf['issue_flg']){
                        case 0:
                            $issue_flg = 'X';
                            break;
                        case 1:
                            $issue_flg = '○';
                            break;
                        case 2:
                            $issue_flg = '△';
                            break;
                        default:
                            $issue_flg = '-';
                            break;
                    }
                }else{
                    if($key != 0){
                        $total_pie = '';
                        $total_price = '';
                        $all_cost = '';
                    }
                }

                $csv_inf = array(
                    $reserve_no,
                    $reserve_date,
                    $member_id,
                    $consumer_name,
                    $tel_num,
                    $mail_address,
                    $pay_method,
                    $pickup_method,
                    $payment_flg,
                    $issue_flg,
                    $visit_flg,
                    $visit_date,
                    $visit_gate, //STS 2021/09/01 Task 49
                    $refund,
                    $total_pie,
                    $total_price,
                    $all_cost,
                    $seat_no,
                    $seat_seq ,
                    $seat_title,
                    $ticket_title,
                    $seat_type,
                    $seat_position,
                    $reference_number,
                    $price,
                    $order_cancel_reason,
                    // STS 2021/08/13 task 45 -- START
                    $tel,
                    $email,
                    $allow_email,
                    // STS 2021/08/13 task 45 -- END
                );   
   
                //フリーアンケート 2021/04/09 LS-Itabashi
                foreach($questionnaires as $questionnaire) {
                    if($seat['seatType'] == 3) {
                        $csv_inf[] = '-';   //質問
                        $csv_inf[] = '-';   //回答
                    } else {
                        $csv_inf[] = $questionnaire['question_lang_ja'][0]['question_text'];
                        $csv_inf[] = array_key_exists($questionnaire['question_id'], $order['questionAnswers']) ? $order['questionAnswers'][$questionnaire['question_id']] : '';
                    }
                }

               array_push($result, $csv_inf);
            }
        }

        return $result;
    }

    /**
     * 訂單資料整理成 csv 格式 
     * 
     * @param array $reservation_data
     * @return array $result
     */
    public function getOrdersCsv($orders){
        $result = array();
            
        $csv_title = array(
            trans('sellManage.S_EventDetailTable'),
            trans('sellManage.S_EventTitle'),
            trans('sellManage.S_EventDetailTableDate'),
            trans('sellManage.S_EventOpenDate'),
            trans('sellManage.S_EventOpenTime'),
            trans('sellManage.S_EventTimeSlot'),
            trans('sellManage.S_EventDetailTableId'),
            trans('sellManage.S_EventDetailTableName'),
            trans('sellManage.S_EventDetailCSVTel'),
            trans('sellManage.S_EventDetailCSVMail'),
            trans('sellManage.S_EventDetailTablePay'),
            trans('sellManage.S_EventDetailTableGet'),
            trans('sellManage.S_EventDetailTableBill'),
            trans('sellManage.S_EventDetailTableTicketing'),
            trans('sellManage.S_EventDetailTableVisit'),
            trans('sellManage.S_EventDetailTableVisitDate'),
            trans('sellManage.S_EventDetailTableComplete'),
            trans('sellManage.S_EventDetailTableNum'),
            trans('sellManage.S_EventDetailTableTPSum'),
            trans('sellManage.S_EventDetailTableTotal'),
            trans('sellManage.S_Orderidno'),
            trans('sellManage.S_Seatidno'),
            trans('sellManage.S_EventDetailSeatName'),
            trans('sellManage.S_EventDetailTicketName'),
            trans('sellManage.S_EventDetailSeatType'),
            trans('sellManage.S_EventDetailSeatPosition'),
            trans('sellManage.S_EventDetailReferencenNumber'),
            trans('sellManage.S_EventDetailTableTP'),
            trans('sellManage.S_ReserveCancel'),
            trans('sellManage.S_EventDetailTel'),
            trans('sellManage.S_EventDetailEmail'),
            trans('sellManage.S_EventDetailAllowEmail'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns'),
            trans('sellManage.S_FreeQuestionDesc'),
            trans('sellManage.S_FreeQuestionAns')
        );
      
        array_push($result, $csv_title);

        foreach($orders as $order){            
            $reserve_no = ($order->reserve_no)?$order->reserve_no:'-';
            $reserve_date = ($order->reserve_date)?$order->reserve_date:'-';
            $consumer_name = ($order->consumer_name)?GLHelpers::hideInformation($order->consumer_name):'-';
            $tel_num = ($order->tel_num)?GLHelpers::hideInformation($order->tel_num):'-';
            $mail_address = ($order->mail_address)?GLHelpers::hideInformation($order->mail_address, 'email'):'-';
            $pay_method = '';
            $pickup_method = '';
            $payment_flg = '';
            $issue_flg = '';
            $refund = '';
            $total_pie = '-';
            $total_price = '-';
            $all_cost = '-';
            $order_cancel_reason = '-';
            $member_id = '';
            $current_tel = '-';
            $current_email = '-';
            $current_allow_email = '-';
            $current_status = '';

            if($order->member_id){
                $member_id_t = str_replace('gettiis$', '', $order->member_id);

                if(preg_match("/[N_M]/", $member_id_t)){
                    // 非會員
                    $member_id =  trans('sellManage.S_IsNonMember');
                }
                else {
                    $member_id = $order->member_id;
                    $dataMember = $this->MemberRepositories->getMemberData($member_id_t);
                    // $dataReser = $this->SellManageRepositories->getReservationData2($member_id);
                    if($dataMember) {
                        foreach($dataMember as $test){
                            if ($test['system_kbn'] === 1) {
                                // GETTIIS會員
                                if ($test['status'] == 2) {
                                    // 正常的會員
                                    $current_tel = $test['tel_num'];
                                    $current_email = $test['mail_address'];
                                    $current_allow_email = $test['allow_email']?trans('sellManage.S_AllowMail'):trans('sellManage.S_DisallowMail');
                                }
                           } else {
                               // 非GETTIIS會員
                           }
                        }
                    }
                    else {
                        // 找不到會員資資料
                        // foreach($dataReser as $data){
                        //     $current_tel = $data['tel_num'];
                        //     $current_email = $data['mail_address'];
                        //     $current_allow_email = '??';
                        // }
                    }
                }
            }else{
                // 無member_id
                $member_id = "-";
            }

            if(count($order->seatSale) >= 1){
                $order_payment  = $order->seatSale[0]->payment_flg;
                $order_issue = $order->seatSale[0]->issue_flg;
                $total_pie = 0;
                $total_price = 0;
                $all_cost = 0;

                foreach($order->seatSale as $seatSale){   
                    $seat_commission_sum = $seatSale->commission_sv + $seatSale->commission_payment + $seatSale->commission_ticket + $seatSale->commission_delivery + $seatSale->commission_sub + $seatSale->commission_uc;
                    $sale_price_sum = $seatSale->sale_price + $seat_commission_sum;

                    $total_pie++;
                    $total_price += $sale_price_sum;
                    $all_cost += $sale_price_sum;
                }
                
                //修改訂單金額
                if($order->amountRevise){
                    if($order->amountRevise->amount_status){
                        $all_cost = $order->amountRevise->amount_total;
                    }
                }
            }

            //付款方式
            switch($order->pay_method){
                case -1:
                    $pay_method = 'X';
                    break;
                case 1:
                    $pay_method = trans('sellManage.S_EventDetailCash');
                    break;
                case 2:
                    $pay_method = trans('sellManage.S_EventDetailCreditCard');
                    break;
                case 3:
                    $pay_method = trans('sellManage.S_EventDetailConvenience');
                    break;
                case 31:
                    $pay_method = 'ibon';
                    break;
                case 20:
                    $pay_method = trans('sellManage.S_EventDetailFree');
                    break;
                case 4:
                    $pay_method = '-';
                    break;
                default:
                    $pay_method = '?';
                    break;
            }

            //取票方式
            switch($order->pickup_method){
                case -1:
                    $pickup_method = 'X';
                    break;
                case 3:
                    $pickup_method = trans('sellManage.S_EventDetailConvenience');
                    break;
                case 4:
                    $pickup_method = '-';
                    break;
                case 8:
                    $pickup_method = 'れすQ';
                    break;
                case 11:
                    $pickup_method = '発券なし';
                    break;
                case 9:
                    $pickup_method = trans('sellManage.S_EventDetailPickup_ET');
                    break;
                case 31:
                    $pickup_method = 'ibon';
                    break;
                case 91:
                    $pickup_method = 'QRPASS';
                    break;
                case \Config::get('constant.pickup_method.no_ticketing'):
                    $pickup_method =  trans('sellManage.S_Pickup_NoTicketing');
                    break;
                default:
                    $pickup_method = '?';
                    break;
            }

            for($num = 1; $num < count($order->seatSale); $num++){
                if($order->seatSale[$num]->payment_flg !== $order->seatSale[0]->payment_flg){
                    $order_payment = 2;
                    break;
                }
            }

            for($num = 1; $num < count($order->seatSale); $num++){
                if($order->seatSale[$num]->issue_flg !== $order->seatSale[0]->issue_flg){
                    $order_issue = 2;
                    break;
                }
            }

            //是否付款
            switch($order_payment){
                case 0:
                    $payment_flg = 'X';
                    break;
                case 1:
                    $payment_flg = '○';
                    break;
                case 2:
                    $payment_flg = '△';
                    break;
                default:
                    $payment_flg = '-';
                    break;
            }

            //是否取票
            switch($order_issue){
                case 0:
                    $issue_flg = 'X';
                    break;
                case 1:
                    $issue_flg = '○';
                    break;
                case 2:
                    $issue_flg = '△';
                    break;
                default:
                    $issue_flg = '-';
                    break;
            }

            $refund = '-';

            foreach($order->seatSale as $key => $seat){
                $seat_type = '-';
                $visit_flg = $seat->visit_flg;
                $visit_date = $seat->visit_date;
                $seat_no = $key + 1;
                $seat_seq = $seat->seat_seq;
                $seat_title = ($seat->seat_class_name)?$seat->seat_class_name:'-';
                $ticket_title = ($seat->ticket_class_name)?$seat->ticket_class_name:'-';
                $seat_position = '-';
                $reference_number = '';
                $price = ($seat->price)?$seat->price:'-';
                $performanceName = ($seat->schedule->performance->performance_name)?$seat->schedule->performance->performance_name:'-';;
                $performanceDateDisp = ($seat->schedule->disp_performance_date)?$seat->schedule->disp_performance_date:'-';;
                $performanceDate = ($seat->schedule->performance_date)?$seat->schedule->performance_date:'-';;
                $startTime = ($seat->schedule->start_time)?$seat->schedule->start_time:'-';;

                if($seat->reserve_code){
                    $seat_type = '押え席';
                }else if($seat->alloc_seat_id){
                    $seat_type = '指定席';
                }else if(is_null($seat->alloc_seat_id)){
                    $seat_type = '自由席';
                }

                //保留席-資料
                if($seat->sale_type == 1){
                    $total_pie = 1;
                    $reserve_seq = ($order_inf->reserve_seq)?$order_inf->reserve_seq:'-';
                }else{
                    if($key != 0){
                        $total_pie = '';
                        $total_price = '';
                        $all_cost = '';
                    }
                }

                if($order->cancel_flg == \Config::get('constant.order_cancel_flg.on')){
                    if($order->pay_method == 0) {
                        $order_cancel_reason = trans('sellManage.S_CancelNotice08');
                    }else {
                        if($order->seatSale[0]->seat_status == -2){
                            $order_cancel_reason = trans('sellManage.S_CancelNotice07');
                        }
                        else {
                            $order_cancel_reason = trans('sellManage.S_CancelNotice09');    
                        }
                    }
                }

                //是否入場
                if( 
                    is_null($visit_flg) ||
                    ($order->pickup_method === \Config::get('constant.pickup_method.store') && !$visit_date) ||
                    ($order->pickup_method === \Config::get('constant.pickup_method.no_ticketing') && !$visit_date)
                ){
                    $visit_text = '-';
                }else{
                    if($visit_flg){
                        $visit_text = '済';
                    }else{
                        $visit_text = '未';
                    }
                }

                if(isset($seat->seat->hallSeat)){
                    $hall_seat = $seat->seat->hallSeat;
                    $floor_name = ($hall_seat->floor)?$hall_seat->floor->floor_name:'';
                    $block_name = ($hall_seat->block)?$hall_seat->block->block_name:'';
                    $seat_cols = $hall_seat->seat_cols;
                    $seat_number = $hall_seat->seat_number;
                    $seat_position = $floor_name . '-' . $block_name . '-' . $seat_cols . '-' . $seat_number;
                }
    
                $csv_inf = array(
                    $reserve_no,
                    $performanceName,
                    $reserve_date,
                    $performanceDate,
                    $startTime,
                    $performanceDateDisp,
                    $member_id,
                    $consumer_name,
                    $tel_num,
                    $mail_address,
                    $pay_method,
                    $pickup_method,
                    $payment_flg,
                    $issue_flg,
                    $visit_text,
                    $visit_date,
                    $refund,
                    $total_pie,
                    $total_price,
                    $all_cost,
                    $seat_no,
                    $seat_seq ,
                    $seat_title,
                    $ticket_title,
                    $seat_type,
                    $seat_position,
                    $reference_number,
                    $price,
                    $order_cancel_reason,
                    $current_tel,
                    $current_email,
                    $current_allow_email,
                );   
   
                $question_csv = $this->getCsvQuestion($order->questionAnswer);
              
                if($question_csv){
                    foreach ($question_csv as $value) {
                        $csv_inf[] = $value["question"];
                        $csv_inf[] = $value["answer"];
                    }
                }

               array_push($result, $csv_inf);
            }
        }
        return $result;
    }

    /**
     * 取得使用者銀行資料
     * @param string $glid
     * @return collections $orders_paginate
     */
    public function getBankInf($glid){
        $result = '';

        try{
            Log::info('get bank inf');
            $user = $this->SellManageRepositories->getBankInf($glid);
            $account_type = '';

            switch($user->account_kbn){
                case 1:
                    $account_type = '普通';
                    break;
                case 2:
                    $account_type = '当座';
                    break;
            }

            $bank_inf = array(
                'bank_name' => $user->bank_name,
                'branch_name' => $user->branch_name,
                'account_num' => $account_type.' '.$user->account_num,
                'account_name' => $user->account_name,
            );
            
            $result = $bank_inf;
        }catch(Exception $e){
            Log::error('function services getBankInf :'.$e->getMessage());
            $result = false;
        }finally{ 
            return $result;
        }
    }
    /**
     * 取得使用手續費資料
     * @param string $glid
     * @return string $result
     */
    public function getTransFee($glid){
        $result = null;
        try{
            Log::info('get bank inf');
            $user_ex = $this->UserExRepositories->getTransFee($glid);

            if($user_ex){
                $result = $user_ex->value;
            }
        }catch(Exception $e){
            Log::error('function services getTransFee :'.$e->getMessage());
        }finally{ 
            return $result;
        }
    }
    /**
     * 入場狀態修改
     * @param string $seat_sale_id
     * @param array $data
     * @return array $result
     */
    public function updateVisitStatus($data, $seat_sale_id){
        $result =  array(
            "successus" => false,
            "message" => "",
            "data" => ""
        );

        try{
            $input = array(
                'visit_flg' => $data['visit_flg'],
                'visit_date' => null
            );

            if($input['visit_flg']){
                $input['visit_date'] = Carbon::now();
            }else{
                unset($input['visit_date']);
            }
          
            $seat_sale = $this->SellManageRepositories->updateVisitStatus($input, $seat_sale_id);
   
            if($seat_sale->isNotEmpty()){
                $result["successus"] = true;
                $result["message"] = trans('sellManage.S_AdmissionMessageComplete');
                $result["data"] = $seat_sale->toArray();
            }

        }catch(Exception $e){
            Log::error('function services updateVisitStatus :'.$e->getMessage());
            $result["message"] = trans('sellManage.S_AdmissionMessageFail');
        }finally{ 
            return $result;
        }
    }
}
