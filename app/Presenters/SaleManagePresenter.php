<?php

namespace App\Presenters;

use Carbon\Carbon;

class SaleManagePresenter
{
    protected $PERFORMATION_INF;
    protected $SCHEDULE_INF;
    protected $SEAT_DATA; //STS 2021/08/07 Task 25
    protected $seat_total = 0;
    // protected $free_total = 0;
    protected $free_Unlimit = '';
    protected $order_seat_total = 0;
    // protected $order_free_total = 0;
    protected $sell_seat_total = 0;
    // protected $sell_free_total = 0;
    // protected $rel_issue_total = 0;
    protected $res_total = 0;
    protected $nosell_seat_total = 0;
    // protected $nosell_free_total = 0;
    protected $sub_total = 0;

    public function constructPerfomationInf($perfomation_inf)
    {   
        $this->PERFORMATION_INF = $perfomation_inf;
    }
    public function constructScheduleInf($schedule_inf)
    {   
        $this->SCHEDULE_INF = $schedule_inf;
    }
    public function constructSeatData($seat_Data)
    {
        $this->SEAT_DATA = $seat_Data;
    }
    public function timeTransform($time){
        $dt = Carbon::parse($time);
        
        return $dt->format('H:i');
    }
    public function timestampTransform($val){
        return Carbon::parse($val)->timestamp;
    }
    /**
    * 判斷活動狀態 class
    * @param string $status
    * @param string $sale_type
    * @return string
    */
    public function getSeatSettingTitle()
    {
        $title = '';

        if(!isset($this->PERFORMATION_INF['seatmap_profile_cd'])){
            $title = trans('sellManage.S_EventSeatImage');
        }else {
            $title = trans('sellManage.S_EventSeatSetting');
        }


        return  $title;
    }
    /**
    * 判斷活動狀態 class
    * @param string $status
    * @param string $sale_type
    * @return string
    */
    public function getCancelBtn()
    {   
        if($this->SCHEDULE_INF['cancel_flg'] == 0 && $this->PERFORMATION_INF['trans_flg'] > 0){
           return true;
        }else {
           return false;
        }
    }

    public function getDispStatus()
    {
        return $this->PERFORMATION_INF['disp_status'] >= 3; 
        
    }

    public function getPublished()
    {
        return $this->PERFORMATION_INF['trans_flg'] > 0; 
        
    }

    public function getDate()
    {
        return $this->SCHEDULE_INF['performance_date'].' '.$this->SCHEDULE_INF['start_time']; 
        
    }

    public function getCancelStatus()
    {
        return $this->SCHEDULE_INF['cancel_flg'] !== 0;
    }

    public function getStageName()
    {
     
        return $this->SCHEDULE_INF['stage_name'];
     
        
    }
    public function getSaleTotal()
    {
        //STS 2021/08/07 Task 25
        /*$inf = '';

        if($this->SCHEDULE_INF['SALE'] > 0){
            $had_seat = true;
            $this->seat_total += intval($this->SCHEDULE_INF['SALE']);
            $inf .= strval($this->SCHEDULE_INF['SALE']);
        }else{
            $inf .= '-';
        }

        $inf .= ' / ';
        if($this->SCHEDULE_INF['stock_limit'] !== '' ) {
            if($this->SCHEDULE_INF['stock_limit'] > 0 || ($this->PERFORMATION_INF['sch_kbn'] == 1 && intval($this->SCHEDULE_INF['stock_limit']) >= 0)){
                $this->free_total += intval($this->SCHEDULE_INF['stock_limit']);
                $inf .= strval($this->SCHEDULE_INF['stock_limit']);
            }elseif($this->SCHEDULE_INF['stock_limit'] == 0){
                $this->free_Unlimit = trans('common.S_Unlimited');
                $inf .= trans('common.S_Unlimited');
            }else{
                $inf .= '- ';
            }    
        }
        else{
            $inf .= '-';
        }

        return  $inf;*/
        if($this->SEAT_DATA['seat_total'] === trans('common.S_Unlimited'))
        {
            $this->free_Unlimit = trans('common.S_Unlimited');
        }
        $this->seat_total += intval($this->SEAT_DATA['seat_total']);
        return $this->SEAT_DATA['seat_total'];

    }

    
    public function orderCol()
    {   
        /*$inf = '';
        $had_seat = false;

        if($this->SCHEDULE_INF['cnt_inpay_rev'] >= 0 && !is_null($this->SCHEDULE_INF['cnt_inpay_rev'])){
            $this->order_seat_total += intval($this->SCHEDULE_INF['cnt_inpay_rev']);
            $inf .=  strval($this->SCHEDULE_INF['cnt_inpay_rev']);
        }else{
            $inf .= '-';
        }

        $inf .= ' / ';
        
        if($this->SCHEDULE_INF['cnt_inpay_free'] >= 0 && !is_null($this->SCHEDULE_INF['cnt_inpay_free'])){
            $this->order_free_total += intval($this->SCHEDULE_INF['cnt_inpay_free']);
            $inf .= strval($this->SCHEDULE_INF['cnt_inpay_free']);
        }else{
            $inf .= '- ';
        }
        
        return  $inf;*/
        $this->order_seat_total += intval($this->SEAT_DATA['seat_reservation']);
        return $this->SEAT_DATA['seat_reservation'];
    }

    public function isSellCol()
    {   
        /*$inf = '';

        if($this->SCHEDULE_INF['cnt_sale_rev'] >= 0  && !is_null($this->SCHEDULE_INF['cnt_sale_rev'])){
            $this->sell_seat_total += intval($this->SCHEDULE_INF['cnt_sale_rev']);
            $inf .=  strval($this->SCHEDULE_INF['cnt_sale_rev']);
        }else{
            $inf .= '-';
        }

        $inf .= ' / ';

        if($this->SCHEDULE_INF['cnt_sale_free'] >= 0  && !is_null($this->SCHEDULE_INF['cnt_sale_free'])){
            $this->sell_free_total += intval($this->SCHEDULE_INF['cnt_sale_free']);
            $inf .=  strval($this->SCHEDULE_INF['cnt_sale_free']);
        }else{
            $inf .= '- ';
        }

        return  $inf;*/
        $this->sell_seat_total += intval($this->SEAT_DATA['seat_sold']);
        return $this->SEAT_DATA['seat_sold'];
    }

    public function resCol()
    {   
        /*$inf = '- / -';

        if($this->SCHEDULE_INF["RES"] >= 0 && !is_null($this->SCHEDULE_INF['RES'])){
            $cnt_rev_issue = ($this->SCHEDULE_INF['cnt_rev_issue'])?$this->SCHEDULE_INF['cnt_rev_issue']:0;

            $inf = $cnt_rev_issue;
            $inf .= ' / ';
            $inf .= $this->SCHEDULE_INF["RES"];

            $this->rel_issue_total += $cnt_rev_issue;
            $this->res_total += $this->SCHEDULE_INF["RES"];
        }
       

        return $inf;*/
        $this->res_total += intval($this->SEAT_DATA['seat_res']);
        return $this->SEAT_DATA['seat_res'];
    }

    public function noSellCol()
    {   
        /*$inf = '';
        $sale_seat = $this->SCHEDULE_INF['SALE'] - $this->SCHEDULE_INF['cnt_inpay_rev'] - $this->SCHEDULE_INF['cnt_sale_rev'];
        if($sale_seat > 0){
            $this->nosell_seat_total += intval($sale_seat);
            $inf .= $sale_seat;
        }else{
            $inf .= '-';
        }

        $inf .= ' / ';


        if($this->SCHEDULE_INF['stock_limit'] !== '' ) {
            if($this->SCHEDULE_INF['stock_limit'] > 0 || ($this->PERFORMATION_INF['sch_kbn'] == 1  &&  $this->SCHEDULE_INF['stock_limit'] >= 0 )){
                $no_sell_free = $this->SCHEDULE_INF['stock_limit'] - $this->SCHEDULE_INF['cnt_inpay_free'] - $this->SCHEDULE_INF['cnt_sale_free'];
                $this->nosell_free_total += intval($no_sell_free);
                $inf .= strval($no_sell_free);
            }elseif($this->SCHEDULE_INF['stock_limit'] == 0){
                $inf .= trans('common.S_Unlimited');
            }else{
                $inf .= '- ';
            }    
        }
        else {
            $inf .= '- ';
        }

        // if($inf == ''){
        //     $inf = 0;
        // }
        
        return  $inf;*/
        $inf = '';
        if($this->SEAT_DATA['seat_total'] === trans('common.S_Unlimited'))
        {
            $inf .= trans('common.S_Unlimited');
        }
        else
        {
            $nosell_seat = $this->SEAT_DATA['seat_total'] - $this->SEAT_DATA['seat_reservation'] - $this->SEAT_DATA['seat_sold'];
            $inf = $nosell_seat;
            $this->nosell_seat_total += $nosell_seat;
            
        }
        return $inf;
    }

    
    public function subtotalCol()
    {   
        /*$sub_total = ($this->SCHEDULE_INF['subtotal'])?floor($this->SCHEDULE_INF['subtotal']) : 0;
        $this->sub_total += intval($sub_total);

        return  $sub_total;*/
        $this->sub_total += $this->SEAT_DATA['seat_price'];
        return $this->SEAT_DATA['seat_price'];
    }

    public function AllseatTotal()
    {   
        /*$disp_str = '';

        if($this->seat_total > 0){
            $disp_str .= strval($this->seat_total);
        }else{
            $disp_str .= '-';
        }

        $disp_str .= ' / ';

        if($this->free_Unlimit === trans('common.S_Unlimited')){
            $disp_str .= $this->free_Unlimit;
        }elseif($this->free_total > 0){
            $disp_str .= strval($this->free_total);
        }else{
            $disp_str .= '-';
        }

        return  $disp_str;*/
        if($this->free_Unlimit === trans('common.S_Unlimited')){
            return $this->free_Unlimit;
        }
        else
        {
            return $this->seat_total;
        }
    }

    public function allInpayTotal()
    {   
        /*$disp_str = '';

        if($this->order_seat_total >= 0){
            $disp_str .= strval($this->order_seat_total);
        }else{
            $disp_str .= '-';
        }

        $disp_str .= ' / ';

        if($this->order_free_total >= 0){
            $disp_str .= strval($this->order_free_total);
        }else{
            $disp_str .= '-';
        }

        return  $disp_str;*/
        return $this->order_seat_total;
    }

    public function allSellTotal()
    {   
        /*$disp_str = '';

        if($this->sell_seat_total >= 0){
            $disp_str .= strval($this->sell_seat_total);
        }else{
            $disp_str .= '-';
        }

        $disp_str .= ' / ';

        if($this->sell_free_total >= 0){
            $disp_str .= strval($this->sell_free_total);
        }else{
            $disp_str .= '-';
        }

        return  $disp_str;*/
        $disp_str ='';
        if($this->sell_seat_total > 0)
        {
            $disp_str.= $this->sell_seat_total;
        }
        else
            $disp_str .= '-';
        return $this->sell_seat_total;
    }
    
    public function resTotal()
    {   
        return  $this->res_total;
    }

    public function allUnsellTotal()
    {   
        /*$disp_str = '';

        if($this->nosell_seat_total > 0){
            $disp_str .= strval($this->nosell_seat_total);
        }else{
            $disp_str .= '-';
        }

        $disp_str .= ' / ';

        if($this->free_Unlimit  === trans('common.S_Unlimited')){
            $disp_str .= trans('common.S_Unlimited');
        }elseif($this->nosell_free_total > 0){
            $disp_str .= strval($this->nosell_free_total);
        }else{
            $disp_str .= '-';
        }
        
        return  $disp_str;*/
        if($this->free_Unlimit  === trans('common.S_Unlimited'))
        {
            return $this->free_Unlimit;
        }
        else
        {
            return $this->nosell_seat_total;
        }
    }

    public function allIncomeTotal()
    {   
        return  $this->sub_total;
    }
}