<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'street_address',
        'ward',
        'district',
        'city',
        'latitude',
        'longitude',
    ];

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function  warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}
