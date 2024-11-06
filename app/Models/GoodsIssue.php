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
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getCustomerName()
    {
        return $this->customer ? $this->customer->name : '';
    }

    public function getCustomerPhone()
    {
        return $this->customer ? $this->customer->phone : '';
    }

    public function getCustomerAddress()
    {
        $customer = $this->customer;
        if ($customer->location->street_address) {
            $street_address = $customer->location->street_address . ',';
        } else {
            $street_address = '';
        }
        return  $customer->location->ward
            . ',' . $customer->location->district . ',' . $customer->location->city;
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

    public function getTotalAmount()
    {
        $details = $this->goodsIssueDetails;
        $sum = 0;
        foreach ($details as $detail) {
            $sum += $detail->quantity * $detail->unit_price - $detail->discount;
        }
        return $sum;
    }
}
