<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserManageModel extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'GL_USER';
    protected $primaryKey = 'GLID';

    public $timestamps = true;
    public $incrementing = true;

    public function GsSite()
    {
        return $this->belongsTo('App\Models\GSSiteModel', 'SID');
    }

    public function UserEX()
    {
        return $this->hasMany('App\Models\UserExModel', 'GLID', 'GLID');
    }
}
