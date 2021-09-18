<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleModel extends Model
{
    protected $table = 'GL_SCHEDULE';
    protected $primaryKey = 'schedule_id';
    public $timestamps = true;
    public $incrementing = true;
    
    protected $attributes = [
        'performance_flg' => 1,
        'cancel_flg' => 0,
    ];

    protected $fillable = [
        'performance_id', 
        'performance_date', 
        'performance_flg', 
        'open_date', 
        'start_time',
        'disp_performance_date',
        'sch_kbn', 
        'stcd', 
        'cancel_flg', 
        'refund_st_date', 
        'refund_end_date', 
        'cancel_messgae', 
        'cancel_date', 
        'cancel_account_cd', 
        'update_account_cd',
    ];

    public function performance()
    {
        return $this->belongsTo('App\Models\EvenManageModel', 'performance_id');
    }

    public function user()
    {
        return $this->hasManyThrough(
            'App\Models\UserManageModel',
            'App\Models\EvenManageModel',
            'performance_id',
            'GLID',
            'performance_id',
            'GLID'
        );
    }

    public function generalReservation()
    {
        return $this->hasManyThrough(
            'App\Models\GeneralReservationModel',
            'App\Models\SeatSaleModel',
            'schedule_id',
            'order_id',
            'schedule_id',
            'order_id'
        );
    }

    public function GLVSeatStaticOfStage()
    {
        return $this->hasOne('App\Models\GLVSeatStaticOfStageModel', 'schedule_id');
    }

    public function GLVStatsOfStage()
    {
        return $this->hasOne('App\Models\GLVStatsOfStageModel', 'schedule_id');
    }

    public function Stagename()
    {
        return $this->belongsTo('App\Models\StagenameModel', 'stcd');
    }

    public function SeatSale()
    {
        return $this->hasMany('App\Models\SeatSaleModel', 'schedule_id');
    }

    public function SeatClass()
    {
        return $this->hasMany('App\Models\SeatClassModel', 'performance_id', 'performance_id');
    }
    public function StageSeat()
    {
        return $this->hasMany('App\Models\StageSeatModal', 'schedule_id', 'schedule_id');
    }

    /*
     * 活動場次查詢
     * 
     * @param array time
     */
    public function scopeFindSchedule($query, $schedule_id)
    {
        return $query->when($schedule_id, function ($query) use ($schedule_id){
            $query->where('schedule_id', $schedule_id);
        });
    }   
}
