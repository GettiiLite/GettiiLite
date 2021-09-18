<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysReportModel extends Model
{
    
    //精算区分
    const COMMISSION_CARD_RECEIPT     = 1;  //クレジットカード受領代金
    const COMMISSION_SEVEN_RECEIPT    = 2;  //セブン代理受領代金
    const COMMISSION_CARD_COMMISSION  = 3;  //クレジットカード手数料等
    const COMMISSION_SEVEN_COMMISSION = 4;  //セブンイレブン手数料等
    const COMMISSION_RUNNING          = 5;  //ランニング
    
    //金額区分
    const PAYMENT_TICKET        = 1;  //チケット代金
    const PAYMENT_PICKUP        = 2;  //発券手数料
    const PAYMENT_SERVICE       = 3;  //サービス手数料
    const PAYMENT_CANCEL_FEE    = 4;  //キャンセル代金
    const PAYMENT_AMOUNT        = 5;  //支払手数料
    const PAYMENT_SETTLEMENT    = 6;  //決済手数料
    const PAYMENT_CANCEL_AMOUNT = 7;  //キャンセル手数料
    const PAYMENT_RECEIPT       = 8;  //代理受領手数料
    const PAYMENT_SYSTEM        = 9;  //system利用料
        
    protected $table      = 'GL_SYSREPORT_TMP';
    public $timestamps    = false;
    protected $primaryKey = 'id';
    public $incrementing  = true;
    
    protected $fillable = [
      'id',
      'performance_id',
      'commission_type',
      'payment_type',
      'apply_date',
      'unit_price',
      'number',
      'sheets_number',
      'amount',
    ];

}
