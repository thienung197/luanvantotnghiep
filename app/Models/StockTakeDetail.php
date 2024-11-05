<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTakeDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_take_id',
        'product_id',
        'inventory_quantity',
        'actual_quantity',
        'price'
    ];

    public function stockTake()
    {
        return $this->belongsTo(StockTake::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
