<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelOrderModel extends Model
{
    protected $table        = 'GL_CANCEL_ORDER';
    protected $primaryKey   = 'cancel_order_id';
    public    $timestamps   = true;
    public    $incrementing    = true;

    //Status
    const STATUS_REQUEST    = 1;  //申請
    const STATUS_REFUND     = 2;  //退款中
    const STATUS_CLOSE      = 3;  //完成
    const STATUS_ERROR      = -1; //異常

    //refund_kbn
    const REFUND_OTHERS = 0;  //其他
    const REFUND_BA     = 1;  //銀行帳號
    const REFUND_CC     = 2;  //信用卡


    protected $fillable = [
        'order_id', 
        'status', 
        'status_message', 
        'refund_kbn', 
        'refund_inf', 
        'refund_payment', 
        'update_account_cd', 
        'refund_due_date', 
    ];
}
