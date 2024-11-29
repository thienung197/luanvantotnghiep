<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingNotes extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'provider_id',
        'user_id',
        'status'
    ];
}
