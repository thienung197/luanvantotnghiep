<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'goods_receipt_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'manufacturing_date',
        'expiry_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class);
    }
}
