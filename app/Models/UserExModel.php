<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExModel extends Model
{
    protected $table = 'GL_USER_EX';

    public $timestamps = true;
    public $incrementing = true;
    public $primaryKey = 'GLID'; //Set to use updateorcreate method
    protected $fillable = [
                            'GLID',
                            'parameter',
                            'value',
                            "created_at",
                            "updated_at",
    ];

    public function scopeParameter($query, $parameter)
    {
        return $query->where('parameter', $parameter);
    }
}
