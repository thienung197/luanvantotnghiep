<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'user_id',
        'warehouse_id',
        'status',
        'restock_request_reason_id'
    ];
}
