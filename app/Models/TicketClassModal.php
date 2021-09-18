<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketClassModal extends Model
{    
    // ticket_sales_kbn 
    const TICKET_SALES_EARLY   = 1; //先行
    const TICKET_SALES_NORMAL  = 2; //一般
    const TICKET_SALES_ONSITE  = 3; //当日
    const TICKET_SALES_LOTTERY = 4; //抽選
    
    protected $primaryKey = 'ticket_class_id';
    protected $table = 'GL_TICKET_CLASS';
    public $timestamps = true;
    protected $fillable = ['seat_class_id','ticket_class_name','ticket_class_name_short','ticket_sales_kbn','sheets_unit','disp_order','update_account_cd'];
    public $incrementing = true;

    
}
