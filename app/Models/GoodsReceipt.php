<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'warehouse_id',
        'creator_id',
        'provider_id',
        'discount',
        'status'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
