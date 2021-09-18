<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    
class HallModel extends Model
{
    const HALL_CD_INITIAL = 3000000;  //会場コード
    protected $table = 'GL_HALL';
    protected $primaryKey = 'hall_code';
    public $timestamps = true;
}
