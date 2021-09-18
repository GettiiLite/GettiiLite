<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatClassModel extends Model
{   
    // seat_class_kbn 席種区分
    const SEAT_CLASS_KBN_RESERVE   = 1; //指定席
    const SEAT_CLASS_KBN_UNRESERVE = 2; //自由席
    
     // next_seat_flg 隣席管理フラグ
    const SEAT_CLASS_KBN_NEXT_OFF = 0; //しない
    const SEAT_CLASS_KBN_NEXT_ON  = 1; //する
    
    protected $table = 'GL_SEAT_CLASS';
    public $timestamps = true;
    protected $primaryKey = 'seat_class_id';

    protected $fillable = [
        'performance_id',
        'seat_class_name',
        'seat_class_name_short',
        'seat_class_kbn',
        'next_seat_flg',
        'gate',
        'disp_order',
        'seat_class_color',
        'update_account_cd',
    ];

    public function ticketClass()
    {
        return $this->hasMany('App\Models\TicketClassModal', 'seat_class_id');
    }

    /**
     * 取得指定類型席位
     * 
     * @param array $type
     */
    public function scopeOfType($query, $type)
    {   
        return $query->whereIn('seat_class_kbn', $type);
    }
    /**
     * 取得指定席種
     * 
     * @param array $type
     */
    public function scopeInSeatClass($query, $type)
    {   
        return $query->where('seat_class_name', $type);
    }
    /**
     * 取得指定票種
     * 
     * @param array $type
     */
    // public function scopeInSeatClass($query, $type)
    // {   
    //     return $query->where('seat_class_name', $type);
    // }
}
