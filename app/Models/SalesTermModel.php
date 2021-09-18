<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTermModel extends Model
{
    protected $table = 'GL_SALES_TERM';
    protected $primaryKey = 'term_id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'performance_id',
        'member_kbn',
        'treat_kbn',
        'treat_flg',
        'sales_kbn',
        'reserve_st_kbn',
        'reserve_st_date',
        'reserve_st_days',
        'reserve_st_time',
        'reserve_st_count',
        'reserve_cl_kbn',
        'reserve_cl_date',
        'reserve_cl_days',
        'reserve_cl_time',
        'reserve_cl_count',
        'reserve_period',
        'seat_no_notice',
        'sales_kbn_nm',
        'update_account_cd',
    ];

}
