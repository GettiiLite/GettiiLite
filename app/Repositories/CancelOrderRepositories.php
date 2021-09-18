<?php

namespace App\Repositories;

use App\Models\CancelOrderModel;
use Exception;
use Log;
use App;
use Carbon\Carbon;

class CancelOrderRepositories
{
    /** @var CancelOrderModel */
    protected $CancelOrderModel;

    /**
     * @param GMOTransModel $GMOTransModel
     */
    public function __construct(CancelOrderModel $CancelOrderModel)
    {
        $this->CancelOrderModel = $CancelOrderModel;
    }

    public function getByOrderId($orderId) {
        $this->CancelOrderModel = CancelOrderModel::where('order_id',$orderId)->firstOrFail();
        return $this;
    }

    public function getCancelStatusStr() {
        if(!isset($this->CancelOrderModel))
            return 'ERROR';

        switch($this->CancelOrderModel->status) {
            case CancelOrderModel::STATUS_REQUEST :
                return  trans('member.S_RefundRequest');
            case  CancelOrderModel::STATUS_REFUND :
                return  trans('member.S_Refunding');
            case  CancelOrderModel::STATUS_CLOSE  :
                return  trans('member.S_RefundClose');
            case  CancelOrderModel::STATUS_ERROR  :
            default :
                return trans('member.S_RefundError');
        }
    }

    public function getRefundToolStr() {
        if(!isset($this->CancelOrderModel))
            return '';

        switch($this->CancelOrderModel->refund_kbn) {
            case  CancelOrderModel::REFUND_OTHERS :
                return trans('member.S_RefundOther');
            case  CancelOrderModel::REFUND_BA     :
                return trans('member.S_RefundAccount');
            case  CancelOrderModel::REFUND_CC     :
                return trans('member.S_RefundCard');
        }
    }

    public function getRefundAmtStr() {
        if(!isset($this->CancelOrderModel))
            return '';
        
        if(empty($this->CancelOrderModel->refund_payment))
            return '';
        else
            return ' '.number_format($this->CancelOrderModel->refund_payment);
    }

    public function getCreatedTimeStr() {
        return Carbon::parse($this->CancelOrderModel->created_at)->format('Y-m-d H:i');
    }

    public function getReundTimeStr() {
        if(!empty($this->CancelOrderModel->refund_due_date))
            return Carbon::parse($this->CancelOrderModel->refund_due_date)->format('Y-m-d H:i');
        return '';
    }

    public function getReundInfStr() {
        // dd($this->CancelOrderModel);
        $infoArr = json_decode($this->CancelOrderModel->refund_inf,true);
        // dd($infoArr);
        switch($this->CancelOrderModel->refund_kbn) {
            case  CancelOrderModel::REFUND_OTHERS :
                return '';
            case  CancelOrderModel::REFUND_BA     :
                $retStr = '[' . trans('member.S_RefundAccountNUm') . '] ' . $infoArr['bankName'];
                if(!empty($infoArr['branchName']))
                    $retStr .= ' '.$infoArr['branchName'];
                $retStr .= ' - '. $infoArr['bankAccount'] ;
                return $retStr;
            case  CancelOrderModel::REFUND_CC     :
                return '';
        }
        
    }

}
