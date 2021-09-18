<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralReservationModel extends Model
{
    // pick up method
    const PICKUP_METHOD_MOBAPASS = 9;  //モバパス
    const PICKUP_METHOD_QRPASS   = 91; //QR PASS 
    const PICKUP_METHOD_IBON     = 31; //IBON  
    const PICKUP_METHOD_SEJ      = 3; //SEJ
  
    // mobapass_trans_flg
    const MOBAPASS_TRANS_OFF = 0; //未連携
    const MOBAPASS_TRANS_ON  = 1; //連携済
    
    // mobapass_cancel_flg
    const MOBAPASS_CANCEL_OFF = 0; //未取消
    const MOBAPASS_CANCEL_ON  = 1; //取消済

    // order_cancel_flg
    const ORDER_CANCEL_OFF =  0; //未取消
    const ORDER_CANCEL_ON  =  1; //取消済

    protected $table = 'GL_GENERAL_RESERVATION';
    public $timestamps = true;
    protected $primaryKey = 'order_id';
    protected $guarded = ['order_id'];

    public function amountRevise()
    {
        return $this->hasOne('App\Models\AmountReviseModel', 'order_id');
    }
    public function cancelOrder()
    {
        return $this->hasOne('App\Models\CancelOrderModel', 'order_id');
    }
    public function seatSale()
    {
        return $this->hasMany('App\Models\SeatSaleModel', 'order_id');
    }
    public function seatClass()
    {
        return $this->hasManyThrough(
            'App\Models\SeatClassModel',
            'App\Models\SeatSaleModel',
            'order_id', 
            'seat_class_id',
            'order_id', 
            'seat_class_id'
        );
    }
    public function scopeSeatStatus($query, $status)
    {
        return $query->where('seat_status', $status);
    }
    public function gsSite()
    {
        return $this->hasOne('App\Models\GSSiteModel', 'SID', 'SID');
    }
    /*
     * 訂單時間區間
     * 
     * @param array time
     */
    public function scopeBetweenRserveDate($query, $from, $to)
    {
        return $query->when($from && $to, function ($query) use ($from, $to){
            $query->whereBetween('reserve_date', [$from, $to]);
        });
    }   
    /*
     * 訂單關鍵字查詢
     * 
     * @param array time
     */
    public function scopeReserveKeyword($query, $keyword)
    {
        return $query->when($query, function ($query) use ($keyword){
                    $query->where(function($q) use ($keyword){
                        $q->where('reserve_no', 'like', '%'.$keyword.'%')
                            ->orWhere('member_id', 'like', '%'.$keyword.'%')
                            ->orWhere('consumer_name', 'like', '%'.$keyword.'%')
                            ->orWhere('mail_address', 'like', '%'.$keyword.'%')
                            ->orWhere('tel_num', 'like', '%'.$keyword.'%')
                            ->orWhere(function ($query) use ($keyword) {
                                $query->whereHas('seatSale.schedule.Stagename', function ($query) use ($keyword){
                                    $query->where('stage_name', 'like', '%'.$keyword.'%');
                                });
                            });
                    });
                });
    }   
    /*
     * 訂單支付方式
     * 
     * @param array $pickup_method
     */
    public function scopeBetweenPayMethod($query, $pay_method)
    {
        return $query->whereIn('pay_method', $pay_method);
    }  
    /*
     * 訂單取票方式
     * 
     * @param array $pickup_method
     */
    public function scopeBetweenPickupMethod($query, $pickup_method)
    {
        return $query->whereIn('pickup_method', $pickup_method);
    }  

    
    public function questionAnswer()
    {
        return $this->hasMany('App\Models\QuestionAnswerModel', 'order_id');
    }
}
