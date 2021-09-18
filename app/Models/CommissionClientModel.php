<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionClientModel extends Model
{
    
    //手数料区分
    const SYSTEM_COMMISSION = 0;  //システム手数料
    const CREDIT_PAYMENT    = 1;  //クレジット決済手数料
    const CREDIT_CANCEL     = 2;  //クレジット売上取消手数料
    const SEVEN_PAYMENT     = 3;  //セブン決済手数料
    const SEVEN_PICKUP      = 4; //セブン発券手数料
    
        
    protected $table = 'GL_COMMISSION_CLIENT';
   public $timestamps    = true;
    public $incrementing = true;
    protected $fillable = [
                'id' ,
                'GLID', 
                'commission_type',
                'apply_date',
                'rate',
                'amount',
                'delete_flg',
                'update_account_cd' 
               ];
}
