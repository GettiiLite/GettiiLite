<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * Create by STS 2021/08/16 Task 45
 */
class GettiisUserModel extends Eloquent 
{
    protected $connection = 'mysqlGettiis';
    protected $table = 'users';

    
}
