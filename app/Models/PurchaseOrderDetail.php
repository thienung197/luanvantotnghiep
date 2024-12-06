<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function goodsReceipt()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function receivingDetails()
    {
        return $this->hasManyThrough(
            ReceivingDetail::class,
            ReceivingNotes::class,
            'purchase_order_id', // Foreign key on ReceivingNote table
            'receiving_notes_id', // Foreign key on ReceivingDetail table
            'purchase_order_id', // Local key on PurchaseOrderDetail
            'id' // Local key on ReceivingNote
        );
    }
}
