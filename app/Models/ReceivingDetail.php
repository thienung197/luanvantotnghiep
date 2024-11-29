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
}
