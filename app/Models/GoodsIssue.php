<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsIssue extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'creator_id',
        'warehouse_id',
        'customer_id',
        'discount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goodsIssueDetails()
    {
        return $this->hasMany(GoodsIssueDetail::class);
    }

    public function getCustomerName()
    {
        return $this->customer->name;
    }

    public function getWarehouseName()
    {
        return $this->warehouse->name;
    }
}
