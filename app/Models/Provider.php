<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'status',
        'address',
        'location_id'
    ];
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
