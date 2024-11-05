<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTake extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'date',
        'user_id',
        'warehouse_id',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockTakeDetails()
    {
        return $this->hasMany(StockTakeDetail::class);
    }

    public function getWarehouseName()
    {
        return $this->warehouse->name;
    }

    public function getUserName()
    {
        return $this->user->name;
    }
}
