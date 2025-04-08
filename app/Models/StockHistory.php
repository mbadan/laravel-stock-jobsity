<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockHistory extends Model
{   
    use HasFactory;
    
    protected $table = 'stock_history';

    protected $fillable = [
        'symbol', 'name', 'open', 'high', 'low', 'close'
    ];
}
