<?php

namespace App\Presenters;

class SaleIndexPresenter
{
    protected $PERFORMATION_INF;
    protected $seat_total = 0;
    protected $free_total = 0;
    protected $free_Unlimit = '';
    protected $order_seat_total = 0;
    protected $order_free_total = 0;
    protected $sell_seat_total = 0;
    protected $sell_free_total = 0;
    protected $rel_issue_total = 0;
    protected $res_total = 0;
    protected $nosell_seat_total = 0;
    protected $nosell_free_total = 0;
    protected $sub_total = 0;

    /**
    * 判斷活動狀態 class
    * @param string $status
    * @param string $sale_type
    * @return string
    */
    public function constructPerfomationInf($perfomation_inf)
    {   
        $this->PERFORMATION_INF = $perfomation_inf;
    }

    public function getPerformanceDispStatusStr()
    {
      $dispStatus = $this->PERFORMATION_INF['disp_status'];

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
    
    public function totalCol()
    {   
        $inf = '';
        
        if($this->PERFORMATION_INF['SALE'] > 0 && !is_null($this->PERFORMATION_INF['SALE'])){
            $this->seat_total += intval($this->PERFORMATION_INF['SALE']);
            $inf .= strval($this->PERFORMATION_INF['SALE']);
        }else{
            $inf .= '- ';
        }

        $inf .= ' / ';
        if($this->PERFORMATION_INF['stock_limit'] !== '') {
            if($this->PERFORMATION_INF['stock_limit'] > 0 || ($this->PERFORMATION_INF['sch_kbn'] == 1  && $this->PERFORMATION_INF['stock_limit'] >= 0)){
                $this->free_total += intval($this->PERFORMATION_INF['stock_limit']);
                $inf .= strval($this->PERFORMATION_INF['stock_limit']);
            }elseif($this->PERFORMATION_INF['stock_limit'] === 0){
                $this->free_Unlimit = trans('common.S_Unlimited');
                $inf .= trans('common.S_Unlimited');
            }else{
                $inf .= '- ';
            }
        }
        else {
            $inf .= '- ';
        }

        return  $inf;
    }

    public function orderCol()
    {   
        $inf = '';

        if($this->PERFORMATION_INF['cnt_inpay_rev'] >= 0  && !is_null($this->PERFORMATION_INF['cnt_inpay_rev'])){
            $this->order_seat_total += intval($this->PERFORMATION_INF['cnt_inpay_rev']);
            $inf .=  strval($this->PERFORMATION_INF['cnt_inpay_rev']);
        }else{
            $inf .= '-';
        }

        $inf .= ' / ';

        if($this->PERFORMATION_INF['cnt_inpay_free'] >= 0  && !is_null($this->PERFORMATION_INF['cnt_inpay_free'])){
            $this->order_free_total += intval($this->PERFORMATION_INF['cnt_inpay_free']);
            $inf .=  strval($this->PERFORMATION_INF['cnt_inpay_free']);
        }else{
            $inf .= '- ';
        }

        return  $inf;
    }

    public function isSellCol()
    {   
        $inf = '';

        if($this->PERFORMATION_INF['cnt_sale_rev'] >= 0  && !is_null($this->PERFORMATION_INF['cnt_sale_rev'])){
            $this->sell_seat_total += intval($this->PERFORMATION_INF['cnt_sale_rev']);
            $inf .=  strval($this->PERFORMATION_INF['cnt_sale_rev']);
        }else{
            $inf .= '- ';
        }

        $inf .= ' / ';

        if($this->PERFORMATION_INF['cnt_sale_free'] >= 0  && !is_null($this->PERFORMATION_INF['cnt_sale_free'])){
            $this->sell_free_total += intval($this->PERFORMATION_INF['cnt_sale_free']);
            $inf .=  strval($this->PERFORMATION_INF['cnt_sale_free']);
        }else{
            $inf .= '- ';
        }
        
        return  $inf;
    }

    public function resCol()
    {   
        $inf = '- / -';

        if($this->PERFORMATION_INF["RES"] >= 0 && !is_null($this->PERFORMATION_INF['RES'])){
            $cnt_rev_issue = ($this->PERFORMATION_INF['cnt_rev_issue'])?$this->PERFORMATION_INF['cnt_rev_issue']:0;

            $inf = $cnt_rev_issue;
            $inf .= ' / ';
            $inf .= $this->PERFORMATION_INF["RES"];

            $this->rel_issue_total += $cnt_rev_issue;
            $this->res_total += $this->PERFORMATION_INF["RES"];
        }
       

        return $inf;
    }

    public function noSellCol()
    {   
        $inf = '';
        $had_seat = false;
        $sale_seat = $this->PERFORMATION_INF['SALE'] - $this->PERFORMATION_INF['cnt_inpay_rev'] - $this->PERFORMATION_INF['cnt_sale_rev'];

        if($this->PERFORMATION_INF['SALE'] >= 0  && !is_null($this->PERFORMATION_INF['SALE'])){
            $had_seat = true;
            $this->nosell_seat_total += intval($sale_seat);
            $inf .= $sale_seat;
        }else{
            $inf .= '- ';
        }

        $inf .= ' / ';

        if(!is_null($this->PERFORMATION_INF['stock_limit']) && ($this->PERFORMATION_INF['stock_limit'] > 0 || ($this->PERFORMATION_INF['sch_kbn'] == 1 && $this->PERFORMATION_INF['stock_limit'] >= 0) )){
            $no_sell_free = intval($this->PERFORMATION_INF['stock_limit']) - $this->PERFORMATION_INF['cnt_inpay_free'] - $this->PERFORMATION_INF['cnt_sale_free'];
            $this->nosell_free_total += intval($no_sell_free);
            $inf .= strval($no_sell_free);
        }elseif($this->PERFORMATION_INF['stock_limit'] === 0){
            $inf .= trans('common.S_Unlimited');
        }else{
            $inf .= '- ';
        }

        return  $inf;
    }

    
    public function subtotalCol()
    {   
        $sub_total = ($this->PERFORMATION_INF['subtotal'])?floor($this->PERFORMATION_INF['subtotal']) : 0;
        $this->sub_total += intval($sub_total);

        return  $sub_total;
    }

    public function AllseatTotal()
    {   
        $disp_str = '';

        if($this->seat_total > 0){
            $disp_str .= strval($this->seat_total);
        }else{
            $disp_str = '-';
        }

        $disp_str .= ' / ';

        if($this->free_Unlimit === trans('common.S_Unlimited')){
            $disp_str .= $this->free_Unlimit;
        }elseif($this->free_total > 0){
            $disp_str .= strval($this->order_free_total);
        }else{
            $disp_str = '-';
        }

        return  $disp_str;
    }

    public function allInpayTotal()
    {   
        $disp_str = '';

        if($this->order_seat_total >= 0){
            $disp_str .= strval($this->order_seat_total);
        }else{
            $disp_str = '-';
        }

        $disp_str .= ' / ';

        if($this->order_free_total >= 0){
            $disp_str .= strval($this->order_free_total);
        }else{
            $disp_str .= '-';
        }


        return  $disp_str;
    }

    public function allSellTotal()
    {   
        $disp_str = '';

        if($this->sell_seat_total > 0){
            $disp_str .= strval($this->sell_seat_total);
        }else{
            $disp_str = '-';
        }

        $disp_str .= ' / ';

        if($this->sell_free_total > 0){
            $disp_str .= strval($this->sell_free_total);
        }else{
            $disp_str = '-';
        }

        return  $disp_str;
    }
    
    public function resTotal()
    {   
        return  $this->rel_issue_total.' / '.$this->res_total;
    }

    public function allUnsellTotal()
    {   
        $disp_str = '';

        if($this->nosell_seat_total > 0){
            $had = true;
            $disp_str .= strval($this->nosell_seat_total);
        }else{
            $disp_str = '-';
        }

        $disp_str .= ' / ';

        if($this->nosell_free_total > 0){
            $disp_str .= strval($this->nosell_free_total);
        }else{
            $disp_str = '-';
        }

        return  $disp_str;
    }

    public function allIncomeTotal()
    {   
        return  $this->sub_total;
    }
}