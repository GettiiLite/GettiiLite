<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StageSeatModal extends Model
{
    protected $table = 'GL_STAGE_SEAT';
    protected $primaryKey = 'stage_seat_id';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'alloc_seat_id',
        'schedule_id',
        'seat_class_id',
        'reserve_code',
        'update_account_cd',
    ];

    public function seat() 
    {
        return $this->belongsTo('App\Models\SeatModel', 'alloc_seat_id', 'alloc_seat_id');
    }
}
