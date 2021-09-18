<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveModel extends Model
{
    protected $table = 'GL_RESERVE';
    protected $primaryKey = 'reserve_code';

    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'performance_id', 
        'reserve_name', 
        'reserve_symbol', 
        'reserve_color', 
        'reserve_word_color', 
        'sys_reserve_flg', 
        'update_account_cd'
    ];
}
