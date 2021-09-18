<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GSSiteModel extends Model
{
    protected $table = 'GL_GSSITE';
    protected $primaryKey = 'SID';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'aid', 
        'xcdkey', 
        'url_gs', 
        'url_api', 
    ];
}