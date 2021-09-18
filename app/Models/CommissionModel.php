<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionModel extends Model
{
    protected $table = 'GL_COMMISSION';
    protected $primaryKey = 'commission_code';

    public $timestamps = true;
    public $incrementing = true;
}
