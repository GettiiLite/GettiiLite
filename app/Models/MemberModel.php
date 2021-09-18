<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Create by STS 2021/08/16 Task 45
 */
class MemberModel extends Model
{
    protected $table = "GL_MEMBER";
    protected $primaryKey = 'ID';
    public $timestamps = true;
    public $incrementing = true; 
    
    protected $filltable = [
        'ID',
        'member_id',
        'tel_num',
        'mail_address',
        'allow_email',
        'status',
        'system_kbn',
        'update_account_cd',
    ];

}
