<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallSeatModel extends Model
{
    protected $table = 'GL_HALL_SEAT';
    protected $primaryKey = 'seat_id';
    public    $timestamps    = true;
    public    $incrementing  = true;

    protected $fillable = [
        'profile_id',
        'floor_id',
        'block_id',
        'seat_seq',
        'x_coordinate',
        'y_coordinate',
        'x_position',
        'y_position',
        'seat_angle',
        'seat_cols',
        'seat_number',
        'gate',
        'prio_floor',
        'prio_seat',
        'update_account_cd'
    ];


    public function floor()
    {
        return $this->belongsTo('App\Models\FloorModel', 'floor_id');
    }

    public function block()
    {
        return $this->belongsTo('App\Models\BlockModel', 'block_id');
    }
}
