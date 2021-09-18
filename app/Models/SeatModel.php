<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatModel extends Model
{
    protected $table = 'GL_SEAT';
    protected $primaryKey = 'alloc_seat_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $guarded = ['alloc_seat_id'];
    
    public function reserve()
    {
        return $this->belongsTo('App\Models\ReserveModel', 'reserve_code');
    }

    public function hallSeat()
    {
        return $this->belongsTo('App\Models\HallSeatModel', 'seat_id');
    }
    
    public function stageSeats()
    {
        return $this->hasMany('App\Models\StageSeatModal', 'alloc_seat_id', 'alloc_seat_id');
    }

    public function seatClass()
    {
        return $this->belongsTo('App\Models\SeatClassModel', 'seat_class_id');
    }
}
