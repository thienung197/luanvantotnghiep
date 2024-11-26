<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsIssue extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'status',
        'approved_by',
        'customer_id',
        'discount',
    ];



    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getCustomerLocationId()
    {
        return optional($this->customer)->location->id ?? '';
    }

    public function getCustomerCode()
    {
        return $this->customer ? $this->customer->code : '';
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

        if (!$customer || !$customer->location) {
            return '';
        }

        $street = $customer->location->street_address ?? '';
        $ward = $customer->location->ward ?? '';
        $district = $customer->location->district ?? '';
        $city = $customer->location->city ?? '';

        $addressParts = array_filter([$street, $ward, $district, $city]);

        return   implode(', ', $addressParts);
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

    public function goodsIssueBatches()
    {
        return $this->hasMany(GoodsIssueBatch::class);
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
