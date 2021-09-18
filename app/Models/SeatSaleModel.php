<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class SeatSaleModel extends Model
{
    // 席位狀態
    const SEAT_STATUC_CANCEL = -99; //指定席
    const EXPIRETIME = 10;

    protected $table = 'GL_SEAT_SALE';
    protected $primaryKey = 'seat_sale_id';
    public $timestamps = true;
    public $incrementing = true;
    protected $guarded = ['seat_sale_id'];

    protected $fillable = [
        'visit_flg', 
        'visit_date', 
    ];

    public function generalReservation()
    {
        return $this->belongsTo('App\Models\GeneralReservationModel', 'order_id');
    }
    public function scopeSeatStatus($query, $status)
    {
        return $query->whereIn('seat_status', $status);
    }
    public function scopeSaleType($query, $status)
    {
        return $query->when($status, function ($query) use ($status){
            $query->whereIn('sale_type', $status);
        });
    }
    public function scopeSeatStatusNotCancel($query)
    {
        return $query->where('seat_status', '>', 0);
    }
    public function scopeTmpResvSeat($query) {
        return $this->scopeSeatStatus($query, [1]);
    }
    public function scopeExpireTmpResvSeat($query) {
        $now = date("Y-m-d H:i:s");
        $expir_str = '-'.self::EXPIRETIME.' minutes';
        $add_time = strtotime($expir_str, strtotime($now)); 
        $expire = date('Y-m-d H:i:s', $add_time); 

        return $this->where("temp_reserve_date", '<',$expire)->tmpResvSeat($query);
    }
    /*
     * 取票狀態 
     * 
     * @param int $issue
     */
    public function scopeSeatIssue($query, $issue)
    {
        return $query->whereIn('issue_flg', $issue);
    }
    /*
     * 是否付款
     * 
     * @param int $issue
     */
    public function scopePaymentFlg($query, $payment_flg)
    {
        return $query->whereIn('payment_flg', $payment_flg);
    }
    public function schedule()
    {
        return $this->belongsTo('App\Models\ScheduleModel', 'schedule_id');
    }
    public function seat()
    {
        return $this->belongsTo('App\Models\SeatModel', 'alloc_seat_id');
    }
    public function seatClass()
    {
        return $this->belongsTo('App\Models\SeatClassModel', 'seat_class_id');
    }
}
