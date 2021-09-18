<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StagenameModel extends Model
{
    protected $table = 'GL_STAGENAME';
    protected $primaryKey = 'stcd';
    public $timestamps = true;
    public $incrementing = true;
    protected $attributes = [
        'stage_disp_flg' => 0,
    ];
    protected $fillable = [
        'performance_id',
        'stage_name',
        'stage_num',
        'stage_disp_flg',
        'description',
        'update_account_cd'
    ];
}