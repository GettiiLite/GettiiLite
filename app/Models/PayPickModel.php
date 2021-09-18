<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayPickModel extends Model
{
    
    //支払方法
    const PAY_METHOD_NOT    = 0;  //無
    const PAY_METHOD_CASH   = 1;  //現金 cash
    const PAY_METHOD_CARD   = 2;  //カード credit card
    const PAY_METHOD_STORE  = 3;  //コンビニ convenience store
    const PAY_METHOD_FREE   = 20; //無料
    const PAY_METHOD_IBON   = 31; //ibon
    
    //引取方法
    const PICKUP_METHOD_ETICOKET     = 9;  //電子チケット mobapass
    const PICKUP_METHOD_QRPASS_SMS   = 91; //電子チケット QR PASS SMS
    const PICKUP_METHOD_QRPASS_EMAIL = 91; //電子チケット QR PASS EMAIL
    const PICKUP_METHOD_RESUQ        = 8; //電子チケット れすQ
    const PICKUP_METHOD_NOTICKETING  = 99; //電子チケット 発券なし
    const PICKUP_METHOD_STORE        = 3;  //コンビニ SEJ convenience store
    const PICKUP_METHOD_ONSITE       = 1;  //窓口 onsite
    const PICKUP_METHOD_IBON         = 31; //ibon(TW)
    const PICKUP_METHOD_NO_TICKETING    = 99; //no ticket
   
    protected $table = 'GL_PAY_PICK';
    protected $primaryKey = 'paynpick_id';

    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'term_id',
        'pay_method',
        'pickup_method',
        'treat_flg',
        'treat_end_kbn',
        'treat_end_date',
        'treat_end_days',
        'treat_end_time',
        'pay_due_days',
        'pickup_st_kbn',
        'pickup_st_date',
        'pickup_st_days',
        'pickup_st_time',
        'pickup_st_count',
        'pickup_due_kbn',
        'pickup_due_date',
        'pickup_due_days',
        'pickup_due_time',
        'pickup_due_count',
        'receive_limit',
        'update_account_cd',
    ];

}
