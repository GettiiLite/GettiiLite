<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonreservedStockModal extends Model
{
    protected $table = 'GL_NONRESERVED_STOCK';
    protected $primaryKey = 'stock_id';
    
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
                    'schedule_id',
                    'seat_class_id',
                    'stock_limit',
                    'update_account_cd'];
}
