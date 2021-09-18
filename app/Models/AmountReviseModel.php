<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmountReviseModel extends Model
{
    protected   $table          = 'GL_AMOUNT_REVISE';
    protected   $primaryKey     = 'amount_id';
    public      $timestamps     = true;
    public      $incrementing   = true;

    protected $fillable = [
        'order_id',
        'amount_status',
        'amount_total',
        'revise_info',
        'amount_memo',
        'update_account_cd',
    ];

    public function userAccount()
    {
        return $this->hasOne('App\Models\UserAccountModel', 'update_account_cd');
    }
}
