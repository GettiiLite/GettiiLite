<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GMOTransModel extends Model
{
    protected $table = 'GL_GMO_TRANS';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'order_number',
        'trans_code',
        'user_id',
        'payment_code',
        'state',
        'charge_date',
        'req_result',
        'req_errCode',
        'req_errMsg',
        'redirect_url',
        'checksum',
        'temp_reserve_sn',
        'SID'
        ];
}