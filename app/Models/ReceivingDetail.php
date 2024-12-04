<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'receiving_notes_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'manufacturing_date',
        'expiry_date'
    ];

    public function receivingNote()
    {
        return $this->belongsTo(ReceivingNote::class, 'receiving_notes_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
