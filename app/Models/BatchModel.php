<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Create by STS 2021/08/16 Task 45
 */
class BatchModel extends Model
{
    protected $connection = 'mysql';
    protected $table = "GL_BATCH";
    protected $primaryKey = 'ID';
    public $timestamps = false;
    // public $incrementing = true;

    protected $filltable = [
        'system_kbn',
        'process_kbn',
        'status',
    ];
}