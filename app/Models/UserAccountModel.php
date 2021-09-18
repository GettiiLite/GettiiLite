<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccountModel extends Model
{
    protected $table        =   'GL_ACCOUNT';
    protected $primaryKey   =   'account_cd';
    public $timestamps      =   true;
    public $incrementing    =   true;

    protected $fillable = [
        'GLID', 
        'account_number', 
        'account_code', 
        'password', 
        'expire_date', 
        'mail_address', 
        'profile_info_flg', 
        'event_info_flg', 
        'sales_info_flg', 
        'personal_info_flg', 
        'status', 
        'remarks', 
        'update_account_cd'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\UserManageModel', 'GLID', 'GLID');
    }
}
