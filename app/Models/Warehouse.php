<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'capacity',
        'size',
        'isRefrigerated',
        'location_id'
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function goodsIssues()
    {
        return $this->hasMany(GoodsIssue::class);
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getAddress()
    {
        return $this->location->ward . ' , ' . $this->location->district . ' . ' . $this->location->city;
    }
}
