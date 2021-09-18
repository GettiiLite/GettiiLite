<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceModal extends Model
{
    //取扱区分
    const TICKET_TREAT_NET  = 3; //ネット
    
    protected $table = 'GL_PRICE';

    public $timestamps = true;
    public $incrementing = true;
    protected $primaryKey = 'price_id';

    protected $fillable = [
        'ticket_class_id',
        'member_kbn',
        'price',
        'pattern_code',
        'treat_flg',
        'treat_kbn',
        'update_account_cd'
    ];

}
