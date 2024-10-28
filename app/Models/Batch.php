<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'product_id',
        'price',
        'manufacturing_date',
        'expiry_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
