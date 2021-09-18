<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceLangModel extends Model
{
    protected $table         = 'GL_PERFORMANCE_LANG';
    protected $primaryKey    = 'lang_id';
    public    $timestamps    = true;
    public    $incrementing  = true;

    protected $fillable = [
        'lang_info', 
        'lang_code',
        'performance_id', 
    ];
}
