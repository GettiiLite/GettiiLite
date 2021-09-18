<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftModel extends Model
{
    protected $table         = 'GL_DRAFT';
    protected $primaryKey    = 'draft_id';
    public    $timestamps    = true;
    public    $incrementing  = true;

    protected $fillable = [
        'draft_info', 
        'message', 
        'performance_id', 
        'new_status',
        'update_account_cd', 
    ];
}
