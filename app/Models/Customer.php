<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'gender',
        'phone',
        'location_id'
    ];

    public function goodsIssues()
    {
        return $this->hasMany(GoodsIssue::class);
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
