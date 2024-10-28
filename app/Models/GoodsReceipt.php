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
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function getProviderName()
    {
        return $this->provider->name;
    }

    public function goodsReceiptDetails()
    {
        return $this->hasMany(GoodsReceiptDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getWarehouseName()
    {
        return $this->warehouse->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
