<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GLVStatsOfPerformaceModel extends Model
{
    protected   $table          = 'GL_V_Stats_of_Performace';
    protected   $primaryKey     = 'performance_id';

    // protected $fillable = [
    //     'performance_id',
    //     'status',
    //     's_pid',
    //     'cnt_inpay_rev',
    //     'cnt_inpay_free',
    //     'cnt_sale_rev',
    //     'cnt_sale_free',
    //     'cnt_rev_issue',
    //     'subtotal',
    // ];
    
}
