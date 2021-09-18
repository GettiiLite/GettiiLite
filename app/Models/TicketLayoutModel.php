<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLayoutModel extends Model
{
    protected $table = 'GL_TICKET_LAYOUT';
    protected $primaryKey = 'layout_id';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'performance_id', 
        'schedule_id', 
        'ticket_kbn', 
        'thumbnail', 
        'free_word', 
        'update_account_cd',
    ];

}
