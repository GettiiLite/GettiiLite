<?php

namespace App\Presenters;

use Carbon\Carbon;
use GLHelpers;

class OrdersPresenter
{
    /**
     * @var collections
     */
    public  $ORDER;
    /**
     * @var collections|null
     */
    public  $SEAT_SALE;
    /**
     * @var collections|null
     */
    public  $PERFORMANCE;
    /**
     * @var collections
     */
    public  $SCHEDULE;
    /**
     * @var collections|null
     */
    public  $ANSWERS;

    /**
    * 初始化
    * @param collections $order
    */
    public function constructOder($order)
    {  
        $this->ORDER = $order;
        $this->SEAT_SALE = $order->seatSale;
        $this->SCHEDULE = $this->SEAT_SALE[0]->schedule;
        $this->PERFORMANCE = $this->SCHEDULE->performance;
        $this->ANSWERS = $this->ORDER->questionAnswer;
    }
    /**
    * 活動名稱
    * @param collections $order
    */
    public function getPerfomanceName():string
    {
        return $this->basisFormat($this->PERFORMANCE->performance_name);
    }
    /**
    * 活動日期
    * @param collections $order
    */
    public function getPerfomanceData()
    {
        $date = Carbon::parse($this->SCHEDULE->performance_date.'  '.$this->SCHEDULE->start_time);

        return $date->format('Y-m-d H:i');
    }
    /**
    * 判斷活動取消原因
    * @return string $result
    */
    public function ticketprice($item):int
    {
        $commission_sum = intval($item->commission_sv) + intval($item->commission_payment) + intval($item->commission_ticket) + intval($item->commission_delivery) + intval($item->commission_sub) + intval($item->commission_uc);
        $sale_price = intval($item->sale_price) + $commission_sum;
       
        return $sale_price;
    }
    /**
    * 判斷活動取消原因
    * @return string $result
    */
    public function getOrderCancel()
    {   
        $result = '';

        if($this->ORDER->cancel_flg == \Config::get('constant.order_cancel_flg.on')){
            if($this->ORDER->pay_method == 0){
                $result = trans('sellManage.S_CancelNotice08');
            }else{
                if($this->SEAT_SALE[0]->seat_status == -2){
                    $result = trans('sellManage.S_CancelNotice07');
                }else{
                    $result = trans('sellManage.S_CancelNotice09');    
                }
            }
        }

        return $result;
    }
    /**
    * 判斷欄位是否為空
    * @param string $data
    * @return string $result
    */
    public function basisFormat($data)
    {
        $result = $data;

        if(is_null($data) || empty($data)){
            $result = '-'; 
        }

        return $result;
    }
    /**
    * 付款方法文字轉換
    * @return string $result
    */
    public function payMethodFormat()
    {
        $result = '';

        switch($this->ORDER->pay_method){
            case -1:
                $result = 'X';
                break;
            case 1:
                $result = trans('sellManage.S_EventDetailCash');
                break;
            case 2:
                $result = trans('sellManage.S_EventDetailCreditCard');
                break;
            case \Config::get('constant.pay_method.free'):
                $result = trans('sellManage.S_EventDetailFree');
                break;
            case 31:
                $result = 'ibon';
                break;
            case 3:
                $result = trans('sellManage.S_EventDetailConvenience');
                break;
            default:
                $result = '-';
        }

        return $result;
    }
    /**
    * 取票方法文字轉換
    * @return string $result
    */
    public function pickupMethodFormat()
    {
        $result = '';

        switch($this->ORDER->pickup_method){
            case -1:
                $result = 'X';
                break;
            case 9:
                $result = trans('sellManage.S_EventDetailPickup_ET') ;
                break;
            case 91:
                $result = 'QR PASS';
                break;
            case 3:
                $result = 'セブン';
                break;
            case 31:
                $result = 'IBON';
                break;
            case 8:
                $result = 'れすQ';
                break;
            case 99:
                $result = '発券無し';
                break;
            default:
                $result = '-';
        }

        return $result;
    }
    /**
     * 取得訂單付款狀態
     * @return intint $result
     */
    public function getPaymentFlg()
    {
        $payment_flg = 0;

        if($this->SEAT_SALE->isNotEmpty()){
            $payment_flg = $this->SEAT_SALE[0]->payment_flg;
            for($num = 1; $num < $this->SEAT_SALE->count(); $num++){
                if($this->SEAT_SALE[$num]->payment_flg !== $payment_flg){
                    $payment_flg = 2;
                    break;
                }
            }
        }

        return $payment_flg;
    }
    /**
    * 是否付款文字轉換
    * @return string $result
    */
    public function paymentFlgFormat()
    {
        $result = '';
        $payment_flg = $this->getPaymentFlg();

        switch($payment_flg){
            case 0:
                $result = trans('sellManage.S_EventDetailNoHad');
                break;
            case 1:
                $result = trans('sellManage.S_EventDetailHad');
                break;
            case 2:
                $result = trans('sellManage.S_EventDetailHad').'※';
                break;
            default:
                $result = '-';
        }
        
        return $result;
    }
    /**
    * 取得出票狀態
    * @return int $issue_flg
    */
    public function getIssueFlg()
    {
        $issue_flg = 0;

        if($this->SEAT_SALE->isNotEmpty()){
            $issue_flg = $this->SEAT_SALE[0]->issue_flg;
            for($num = 1; $num < $this->SEAT_SALE->count(); $num++){
                if($this->SEAT_SALE[$num]->issue_flg !== $issue_flg){
                    $issue_flg = 2;
                    break;
                }
            }
        }

        return $issue_flg; 
    }
    /**
    * 出票文字轉換
    * @return string $result
    */
    public function issueFlgFormat()
    {
        $result = '';
        $issue_flg = $this->getIssueFlg();
        
        switch($issue_flg){
            case 0:
                $result = trans('sellManage.S_EventDetailNotGet');
                break;
            case 1:
                $result = trans('sellManage.S_EventDetailGot');
                break;
            case 2:
                $result = trans('sellManage.S_EventDetailGot').'※';
                break;
            default:
                $result = '-';
        }
        
        return $result;
    }
    /**
    * 是否入場文字轉換
    * @return string $result
    */
    public function getVisitFlg()
    {
        $result = 0;

        $visit_date_null = $this->SEAT_SALE->every(function ($value, $key) {
            return is_null($value->visit_date);
        });
    
        //1:未 2:済 3:済※ 0:-
        if(
            $this->ORDER->pickup_method === \Config::get('constant.pickup_method.store') ||
            ($this->ORDER->pickup_method === \Config::get('constant.pickup_method.no_ticketing') && $visit_date_null)
        ){
            $result = 0;
        }else{
            $visit_flg_had = $this->SEAT_SALE->every(function ($value, $key) {
                return $value->visit_flg == 1;
            });

            $visit_flg_no = $this->SEAT_SALE->every(function ($value, $key) {
                return empty($value->visit_flg);
            });

            if($visit_flg_no && $visit_flg_had){
                $result = 3;
            }else if($visit_flg_had){
                $result = 2;
            }else if($visit_flg_no){
                $result = 1;
            }  
        }

        return $result;
    }
    /**
    * 是否入場文字轉換
    * @return string $result
    */
    public function visitFlgFormat()
    {
        $result = '';
        $visit_flg = $this->getVisitFlg();
       
        switch($visit_flg){
            case -1:
                $result = '-';
                break;
            case 1:
                $result = '未';
                break;
            case 2:
                $result = '済';
                break;
            case 3:
                $result = '済※';
                break;
            default:
                $result = '-';
        }

       
        return $result;
    }
    /**
    * 票總數
    * @return int $result
    */
    public function ticketTotal()
    {
        return $this->SEAT_SALE->count();
    }
   /**
    * 合計金額
    * @return int $result
    */
    public function salePriceTotal()
    {
        $sale_price_sum = 0;

        foreach($this->SEAT_SALE as $item){
            $sale_price_sum += $this->ticketprice($item);
        }

        return $sale_price_sum;
    }
    /**
    * 支払済合計金額
    * @return int $result
    */
    public function receivedAmount()
    {
        $received_amount = 0;

        if($this->SEAT_SALE[0]->payment_flg){
            $commission_sum = intval($this->ORDER->commission_sv) + intval($this->ORDER->commission_payment) + intval($this->ORDER->commission_ticket) + intval($this->ORDER->commission_delivery) + intval($this->ORDER->commission_sub) + intval($this->ORDER->commission_uc);
            $sale_price_total = $this->salePriceTotal();
            $received_amount = $sale_price_total + $commission_sum;
        
            if($this->ORDER->cancel_flg && $this->ORDER->cancelOrder){
                $cancel_order = $this->ORDER->cancelOrder;
                
                $received_amount = $received_amount - intval($cancel_order->refund_payment) - intval($cancel_order->use_point);
            }
        }

        return $received_amount;
    }
    /**
    * 票是否入場
    * @param collections $ticket
    * @return string $result
    */
    public function ticketVisitFlgFormat($ticket)
    {
        $result = '';

        //0:未1:済 日本 7-11 = -
        if( 
            is_null($ticket->visit_flg) ||
            ($this->ORDER->pickup_method === \Config::get('constant.pickup_method.store') && !$ticket->visit_date) ||
            ($this->ORDER->pickup_method === \Config::get('constant.pickup_method.no_ticketing') && !$ticket->visit_date)
        ){
            $result = '-';
        }else{
            if($ticket->visit_flg){
                 $result = '済';
            }else{
                $result = '未';
            }  
        }
        
        return $result;
    }
    /**
    * 席位種類
    * @param collections $ticket
    * @return string $result
    */
    public function seatClassFormat($ticket)
    {
        $result = '';

        if($ticket->alloc_seat_id){
            $result = trans('sellManage.S_EventDetailSelectSeat');
        }else{
            $result = trans('sellManage.S_EventDetailFreeSeat');
        }
        return $result;
    }
    /**
    * 票位置
    * @param collections $ticket
    * @return string $result
    */
    public function seatPositionFormat($ticket)
    {
        $result = '';

        if($ticket->alloc_seat_id){
            $hall_seat = $ticket->seat->hallSeat;
            $floor = $ticket->seat->hallSeat->floor;
            $block = $ticket->seat->hallSeat->block;
            $result = $floor->floor_name . '-' . $block->block_name . '-' . $hall_seat->seat_cols . '-' . $hall_seat->seat_number;
        }else{
            $result = $ticket->seat_seq;
        }
        
        return $result;
    }
    /**
    * 判斷是否能取消訂單
    * @return bool $result
    */
    public function getCancelFlg()
    {
        $result = false;
        $schedule = $this->SEAT_SALE[0]->schedule;
        $payment_flg = $this->getPaymentFlg();
        $issue_flg = $this->getIssueFlg();
        $visit_flg = $this->getVisitFlg();
        $pickup_method = $this->ORDER->pickup_method;
        $pay_method = $this->ORDER->pay_method;
        $cancel_able = true;
        $performance_end = Carbon::parse($schedule->performance_date)->addDay()->isPast();
        $had_issue = ($issue_flg === 0 || ($pickup_method === 9   && $issue_flg !== 0 && $visit_flg === 2));
        $pay_status = (($pay_method === 2 && $payment_flg === 1 ) || ($pay_method === 3 && $payment_flg === 0 ) || ($pay_method === 31) || ($pay_method === 20));

        if($performance_end && $payment_flg == 1){
            $cancel_able = false;
        }
       
        if(
            $cancel_able &&
            $this->ORDER->cancel_flg == \Config::get('constant.order_cancel_flg.off') &&
            $had_issue &&
            $pay_status
        ){
            $result = true;
        }

        return $result;
    }
   /**
    * 訂單取消資訊
    * @return bool $result
    */
    public function getCancelInfFlg()
    {
        return $this->ORDER->cancel_flg == \Config::get('constant.order_cancel_flg.on');
    }
   /**
    * 入場
    * @return string $template
    */
    public function getVisit($ticket)
    {
        $template = '';
        $show = false;
        
        if($ticket->payment_flg && !$ticket->reserve_code){
            switch($this->ORDER->pickup_method){
                case \Config::get('constant.pickup_method.store'):
                    if($ticket->issue_flg){
                        $show = true;
                    }
                    break;
                default:
                    $show = true;
            }
        }

        if($show){
            $template = '<button class="$class" v-on:click="openPopResult($seat_sale_id, $status)">$text</button>';

            if($ticket->visit_flg){
                $template_value = array(
                    '$class' => 'btn btn-inverse-outline btn-mm pull-right',
                    '$seat_sale_id' => $ticket->seat_sale_id,
                    '$status' => '0',
                    '$text' => trans('sellManage.S_AdmissionCancel')
                );
            }else{

                $template_value = array(
                    '$class' => 'btn btn-info-outline btn-mm pull-right',
                    '$seat_sale_id' => $ticket->seat_sale_id,
                    '$status' => '1',
                    '$text' => trans('sellManage.S_Admission')
                );
            }

            $template = strtr($template, $template_value);
        }
      
        return $template;
    }
    /**
    * 會員名稱
    * @param collections $order
    */
    public function getMemberName($member_id):string
    {
        if($member_id){
            $member_id = str_replace('gettiis$', '', $member_id);

            if(preg_match("/[N_M]/", $member_id)){
                $member_id =  trans('sellManage.S_IsNonMember');
            }
        }else{
            $member_id = "-";
        }

        return $member_id;
    }
   /**
    * 問卷資料
    * @return string $template
    */
    public function getQuestion()
    {
        $result = "";

        if($this->ANSWERS->isNotEmpty()){
            $template = '<tr>
                            <td>
                                <span>$question_title</span>
                            </td>
                            <td>
                                <span>$question_text</span>
                            </td>
                            <td>
                                <span>$answer_text</span>
                            </td>
                        </tr>';

            $template_value = array(
                '$question_title' => null,
                '$question_text' => null,
                '$answer_text' => null
            );

            foreach($this->ANSWERS as $answer){
                $locale = \Config::get('app.locale');
                $question = $answer->questionLang->firstWhere('lang_code', $locale);
                $template_value['$answer_text'] = $answer->answer_text;
                
                if($question){
                    if($question['question']->use_flg){
                        $template_value['$question_title'] = $question->question_title;
                        $template_value['$question_text'] = $question->question_text;
                        $result .= strtr($template, $template_value);
                    }
                }
            }
        }
        return $result;
    }
  /**
    * 是否有問卷
    * @return bool $result
    */
    public function hadQuestion()
    {
        $result = false;

        if($this->ANSWERS->isNotEmpty()){
            foreach($this->ANSWERS as $answer){
                $locale = \Config::get('app.locale');
                $question = $answer->questionLang->firstWhere('lang_code', $locale);
                if($question){
                    if($question['question']->use_flg){
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }
    /**
    * 會員 mail 格式轉換
    * @param collections $order
    */
    public function getMail($input):string
    {
        return GLHelpers::hideInformation($this->basisFormat($input), 'email');
    }
    /**
    * 會員 tel 格式轉換
    * @param collections $order
    */
    public function getTel($input):string
    {
        return GLHelpers::hideInformation($this->basisFormat($input));
    }
    /**
    * 會員名稱格式轉換
    * @param collections $order
    */
    public function getConsumerName($input):string
    {
        return GLHelpers::hideInformation($this->basisFormat($input));
    }
}