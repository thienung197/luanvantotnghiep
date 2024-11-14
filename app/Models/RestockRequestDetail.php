<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_request_id',
        'product_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function restockRequest()
    {
        return $this->belongsTo(RestockRequest::class, 'restock_request_id');
    }
}
