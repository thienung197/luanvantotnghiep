<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequestDetail extends Model
{
    use HasFactory;

    protected $fillable=[
        'restock_request_id',
        'product_id',
        'quantity'
    ]
}
